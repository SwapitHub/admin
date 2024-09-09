<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductModel;
use App\Models\Subcategory;
use App\Models\DiamondShape;
use App\Models\ProductPrice;
use App\Models\ProductImageModel;
use App\Models\ProductVideosModel;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use App\Models\CenterStone;
use App\Models\Menu;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Else_;

class ProductController extends Controller
{

    public function fetchProductPriceing(Request $request)
    {
        $rules = [
            'product_sku' => 'required',
            'metalType' => 'required',
            'metalColor' => 'required',
            'diamond_type' => 'required'
        ];
        $messages = [
            'product_sku.required' => 'Product Sku is required.',
            'metalType.required' => 'Metal Type is required.',
            'metalColor.required' => 'Metal Color is required.',
            'diamond_type.required' => 'Diamond Type is required.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $output['res'] = 'error';
            $output['msg'] = $errors;
        }
        try {
            if ($request->metalColor == 'rose' || $request->metalColor == 'Rose') {
                $metalColor = "pink";
            } else {
                $metalColor = $request->metalColor;
            }
            $product_price  = ProductPrice::where('product_sku', $request->product_sku)
                ->where('metalType', $request->metalType)
                ->where('metalColor', $metalColor)
                ->where('diamond_type', $request->diamond_type)
                ->first();
            if ($product_price) {
                $output['res'] = 'success';
                $output['msg'] = 'product price is :';
                $output['data'] = ['price' => round($product_price->price, 0), 'diamond_type' => $product_price->diamond_type, 'metalType' => $product_price->metalType];
            } else {
                $output['res'] = 'error';
                $output['msg'] = 'product price not found';
                $output['data'] = '';
            }
            return response()->json($output, 200);
        } catch (Exception $e) {

            if ($product_price) {
                $output['res'] = 'error';
                $output['msg'] = 'product price is :';
                $output['data'] = $e->getMessage();
            }
            return response()->json($output, 500);
        }
    }

    ## product listing
    public function index(Request $request)
    {

        #####################################################

        $output['res'] = 'success';
        $output['msg'] = 'data retrieved successfully';
        $products = ProductModel::where('menu', 7)
            // ->whereNull('products.parent_sku')
            ->where(function ($query) {
                $query->whereNull('products.parent_sku')
                    ->orWhere('products.parent_sku', '');
            })
            ->where('products.status', 'true');
        // Apply the bridal sets filter
        if ($request->bridal_sets == 'true') {
            $products->whereNotNull('products.matching_wedding_band');
        }

        // Apply sorting based on request
        if (!is_null($request->query('sortby'))) {
            $sortBy = $request->query('sortby');
            if ($sortBy == 'low_to_high') {
                // $products->orderBy('product_price.price', 'asc');
                $products->orderByRaw("CAST(products.white_gold_price AS DECIMAL(12, 4)) ASC");
            } elseif ($sortBy == 'high_to_low') {
                // $products->orderBy('product_price.price', 'desc');
                $products->orderByRaw("CAST(products.white_gold_price AS DECIMAL(12, 4)) DESC");
            } elseif ($sortBy == 'Newest') {
                $products->orderBy('products.created_at', 'DESC');
            } elseif ($sortBy == 'best_seller') {
                $products->where('products.is_bestseller', '1');
            }
        }

        // Apply additional filters
        if (!is_null($request->query('shape'))) {
            $products->where('products.CenterShape', strtoupper(trim($request->query('shape'))));
        }

        if (!is_null($request->query('ring_style'))) {
            $subcatSlugs = explode(',', $request->query('ring_style'));

            ## Fetch corresponding IDs based on slugs
            $subcatIds = Subcategory::whereIn('slug', $subcatSlugs)->pluck('id')->toArray();

            ## If there are IDs, use them in the WHERE clause
            if (!empty($subcatIds)) {
                $products->where(function ($query) use ($subcatIds) {
                    foreach ($subcatIds as $id) {
                        $query->orWhereRaw("FIND_IN_SET(?, products.sub_category)", [$id]);
                    }
                });
            }
        }

        if (!is_null($request->query('subcategory'))) {
            $subcatSlugs = explode(',', $request->query('subcategory'));
            ## Fetch corresponding IDs based on slugs
            $subcatIds = ProductSubcategory::where('category_id', 1)->whereIn('slug', $subcatSlugs)->pluck('id')->toArray();
            ## If there are IDs, use them in the WHERE clause
            if (!empty($subcatIds)) {
                $products->where(function ($query) use ($subcatIds) {
                    foreach ($subcatIds as $id) {
                        $query->orWhereRaw("FIND_IN_SET(?, products.subcategory_ids)", [$id]);
                    }
                });
            }
        }

        // Debug the query
        // $sql = $products->toSql();
        // $bindings = $products->getBindings();

        // dd($sql, $bindings);



        if (!is_null($request->query('metal_color'))) {
            $metalcolor_id = $request->query('metal_color');
            $products->where('products.metalColor_id', $metalcolor_id);
        }

        if (!is_null($request->query('price_range'))) {
            $range = explode(',', $request->query('price_range'));
            $min = $range[0];
            $max = $range[1];
            $products->whereBetween('products.white_gold_price', [$min, $max]);
        }

        ## Apply filters for diamond_type and metalColor
        // $products->where('products.diamond_type', 'natural')  // Filter for natural diamond_type
        //     ->where('products.metalColor', 'White');    // Filter for White metalColor

        ## Count the filtered results
        $products->select('id', 'name', 'product_browse_pg_name', 'slug', 'sku', 'internal_sku', 'menu', 'category_id', 'subcategory_ids', 'white_gold_price', 'yellow_gold_price', 'rose_gold_price', 'platinum_price', 'default_image_url', 'images','is_bestseller','created_at');
        $actual_count = $products->count();

        // Retrieve the products if needed (for pagination or display)
        $productsList = $products->paginate(30);
        $count = $productsList->count();

        if ($count) {
            $productList = [];
            foreach ($productsList as $product) {
                $name = strtolower($product->name);
                $product->name = ucwords($name);
                ## Parse the URL and get the path
                $path = parse_url($product->default_image_url, PHP_URL_PATH);
                $extension = pathinfo($path, PATHINFO_EXTENSION);
                ## create image
                $defaulImg = env('AWS_URL') . 'products/images/' . $product->internal_sku . '/' . $product->internal_sku . '.' . $extension;
                $product->default_image_url = $defaulImg;
                ## product images
                $pro_images =  ProductImageModel::where('product_id', $product['id'])->get();
                $images_arr = [];
                if (count($pro_images) > 0) {
                    foreach ($pro_images as $product_img) {
                        $pimg =   env('AWS_URL') . 'products/images/' . $product['internal_sku'] . '/' . $product_img['image_path'];
                        $images_arr[] = $pimg;
                    }
                } else {
                    $images_arr = json_decode($product['images']);
                };
                $product->images = $images_arr;
                ## product image

                // Get the prices based on different criteria
                $white_gold_price = ProductPrice::where('product_sku', $product['sku'])
                    ->where('metalType', '18kt')
                    ->where('metalColor', 'White')
                    ->where('diamond_type', 'natural')
                    ->first()
                    ->price ?? 0;
                $product->white_gold_price = round($white_gold_price, 0);

                $yellow_gold_price = ProductPrice::where('product_sku', $product['sku'])
                    ->where('metalType', '18kt')
                    ->where('metalColor', 'Yellow')
                    ->where('diamond_type', 'natural')
                    ->first()
                    ->price ?? 0;

                $product->yellow_gold_price = round($yellow_gold_price, 0);

                $rose_gold_price = ProductPrice::where('product_sku', $product['sku'])
                    ->where('metalType', '18kt')
                    ->where('metalColor', 'Pink')
                    ->where('diamond_type', 'natural')
                    ->first()
                    ->price ?? 0;
                $product->rose_gold_price = round($rose_gold_price, 0);

                $platinum_price = ProductPrice::where('product_sku', $product['sku'])
                    ->where('metalType', 'Platinum')
                    ->where('metalColor', 'White')
                    ->where('diamond_type', 'natural')
                    ->first()
                    ->price ?? 0;

                $product->platinum_price = round($platinum_price, 0);

                array_push($productList, $product);
            }
            $output['count'] = $count;
            $output['product_count'] = $actual_count;
            $output['data'] = $productList;
        } else {
            $output['res'] = 'error';
            $output['msg'] = 'No product found!';
            $output['data'] = [];
        }
        return response()->json($output)->header('Cache-Control', 'max-age=86400, public');
        ######################################################

    }



    ## product details
    public function productDetails($entity_id)
    {
        $output['res'] = 'success';
        $output['msg'] = 'data retrieved successfully';

        if (!is_null($entity_id)) {
            $product =  ProductModel::where('entity_id', $entity_id)->orWhere('slug', $entity_id)->first();
            $product['name'] = ucfirst(strtolower(!empty($product['name']) ? $product['name'] : $product['product_browse_pg_name']));
            $product['description'] = ucfirst(strtolower($product['description']));
            // $product['images'] = json_decode($product['images']);
            // $product['videos'] = json_decode($product['videos']);
            ## fetch images from s3 and database
            $pro_images =  ProductImageModel::where('product_id', $product['id'])->get();
            $images_arr = [];
            if (count($pro_images) > 0) {
                foreach ($pro_images as $product_img) {
                    $pimg =   env('AWS_URL') . 'products/images/' . $product['internal_sku'] . '/' . $product_img['image_path'];
                    $images_arr[] = $pimg;
                }
            } else {
                $images_arr = json_decode($product['images']);
            };
            $product['images'] = $images_arr;

            ## fetch videos from database
            $pro_videos = ProductVideosModel::where('product_id', $product['id'])->get();
            if (count($pro_videos) > 0) {
                $videos_by_color  = [];
                foreach ($pro_videos as $product_vid) {
                    $color = $product_vid['color'];
                    $video_path = env('AWS_URL') . 'products/videos/' . $product['internal_sku'] . '/' . $product_vid['video_path'];

                    $videos_by_color[$color] = $video_path;
                }
            } else {
                $videos_by_color  = json_decode($product['videos']);
            };
            $product['videos'] = $videos_by_color;

            $priceData = ProductPrice::where('product_sku', $product['sku'])->where('metalType', '18kt')->where('metalColor', 'White')->where('diamond_type', 'natural')->first();
            $product['white_gold_price'] = round($priceData['price'] ?? 0, 0);
            $product['yellow_gold_price'] = round(ProductPrice::where('product_sku', $product['sku'])->where('metalType', '18kt')->where('metalColor', 'Yellow')->where('diamond_type', 'natural')->first()['price'] ?? 0, 0);
            $product['rose_gold_price'] = round(ProductPrice::where('product_sku', $product['sku'])->where('metalType', '18kt')->where('metalColor', 'Pink')->where('diamond_type', 'natural')->first()['price'] ?? 0, 0);
            $product['platinum_price'] = round(ProductPrice::where('product_sku', $product['sku'])->where('metalType', 'Platinum')->where('metalColor', 'White')->where('diamond_type', 'natural')->first()['price'] ?? 0, 0);
            $product['diamond_type'] = 'natural';
            $product['diamondQuality'] = $priceData['diamondQuality'] ?? 0;
            $product['metalType'] = '18KT Gold';
            $center_stone_options = explode('/', $product['center_stone_options']);
            if (in_array('Princess', $center_stone_options)) {
                $center_stone_options = array_diff($center_stone_options, ['Princess']);
            }
            $product['center_stone_options'] = implode('/', $center_stone_options);
            if (!is_null($product['matching_wedding_band']) || !empty($product['matching_wedding_band'])) {
                ## if matching set exist then reterive tha details and send them
                $is_matchingset = ProductModel::where('sku', $product['matching_wedding_band']);
                if ($is_matchingset->exists()) {
                    $matching_bands_product = $is_matchingset->first();
                    $matching_bands_product->price = round(ProductPrice::where('product_sku', $matching_bands_product['sku'])->where('metalType', '18kt')->where('metalColor', 'White')->where('diamond_type', 'natural')->first()['price'] ?? 0, 0);
                    ## Parse the URL and get the path
                    $pathM = parse_url($matching_bands_product->default_image_url, PHP_URL_PATH);
                    $extensionM = pathinfo($pathM, PATHINFO_EXTENSION);
                    ## create image
                    $MdefaulImg = env('AWS_URL') . 'products/images/' . $matching_bands_product->internal_sku . '/' . $matching_bands_product->internal_sku . '.' . $extensionM;
                    $matching_bands_product->default_image_url = $MdefaulImg;
                    $product['matching_wedding_band'] = $matching_bands_product;
                } else {
                    $product['matching_wedding_band'] = NULL;
                }
            }

            ## check if center stone exist then get the value
            $cenyterStoneArr = [];
            $centerStone =  explode(',', $product->center_stones);

            if (!is_null($centerStone)) {
                foreach ($centerStone as $index => $s_stone) {
                    $s_values = CenterStone::find($s_stone);
                    $cenyterStoneArr[] = $s_values;
                }
            }
            $product['center_stones'] = $cenyterStoneArr;
            if (!is_null($product['similar_products'])) {
                $product['similar_products'] = json_encode($this->getSimilarProducts($product['similar_products']));
            }

            if ($product['parent_sku'] != NULL) {
                $var = ProductModel::where('parent_sku', $product['parent_sku'])->get();
                if ($var->isNotEmpty()) {
                    $collect_fractionsemimount = [];
                    $vardata = [];
                    foreach ($var as $variant) {
                        if (!in_array($variant['fractionsemimount'], $collect_fractionsemimount)) { //check if current date is in the already_echoed array
                            $variantData = [
                                'name' => $variant['name'],
                                'id' => $variant['id'],
                                'slug' => $variant['slug'],
                                'sku' => $variant['fractionsemimount']
                            ];
                            $vardata[] = $variantData;
                        }
                        $collect_fractionsemimount[] = $variant['fractionsemimount'];
                    }
                }
                $product['variants'] = $vardata;
            } else if ($product['parent_sku'] == NULL || empty($product['parent_sku'])) {
                $var = ProductModel::where('parent_sku', $product['sku'])->get();
                if ($var->isNotEmpty()) {
                    $collect_fractionsemimount = [];
                    $vardata = [];
                    foreach ($var as $variant) {
                        if (!in_array($variant['fractionsemimount'], $collect_fractionsemimount)) { //check if current date is in the already_echoed array
                            $variantData = [
                                'name' => ucfirst(strtolower(!empty($variant['product_browse_pg_name']) ? $variant['product_browse_pg_name'] : $variant['name'])),
                                'id' => $variant['id'],
                                'slug' => $variant['slug'],
                                'sku' => $variant['fractionsemimount']
                            ];
                            $vardata[] = $variantData;
                        }
                        $collect_fractionsemimount[] = $variant['fractionsemimount'];
                    }
                } else {
                    $vardata = [];
                }
                $product['variants'] = $vardata;
            }
        }
        $output['from'] = 'db';
        $output['data'] = $product;
        // return response()->json($output)
        // ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        // ->header('Pragma', 'no-cache')
        // ->header('Expires', '0');
        return response()->json($output)
            ->header('Cache-Control', 'max-age=86400, public');
    }

    public function searhSuggestion(Request $request)
    {
        $output['res'] = 'success';
        $output['msg'] = 'suggestions are ...';
        $q = $request->input('q');
        if (!empty($q)) {
            $products = ProductModel::orderBy('entity_id', 'desc')
                ->whereNull('parent_sku')
                ->where('status', 'true')
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "$q%")
                        ->orWhere('sku', 'like', "$q%")
                        ->orWhere('metalColor', 'like', "$q%")
                        ->orWhere('diamondQuality', 'like', "$q%")
                        ->orWhere('diamondQuality', 'like', "$q%")
                        ->orWhere('CenterShape', 'like', "$q%")
                        ->orWhere('white_gold_price', 'like', "$q%")
                        ->orWhere('yellow_gold_price', 'like', "$q%")
                        ->orWhere('rose_gold_price', 'like', "$q%")
                        ->orWhere('fractioncomplete', 'like', "$q%")
                        ->orWhere('metalType', 'like', "$q%")
                        ->orWhere('metalWeight', 'like', "$q%")
                        ->orWhere('finishLevel', 'like', "$q%");
                })
                ->select('name', 'product_browse_pg_name', 'fractionsemimount', 'slug', 'menu', 'default_image_url', 'white_gold_price', 'sku', 'type', 'internal_sku')
                ->limit(5)
                ->get();
            $searched_product = [];
            foreach ($products as $product) {
                $product->description = ucfirst(strtolower($product->description));
                $product->images = json_decode($product->images);
                $path = parse_url($product->default_image_url, PHP_URL_PATH);
                $extension = pathinfo($path, PATHINFO_EXTENSION);
                ## create image
                $defaulImg = env('AWS_URL') . 'products/images/' . $product->internal_sku . '/' . $product->internal_sku . '.' . $extension;
                $product->default_image_url = $defaulImg;
                $product->videos = json_decode($product->videos);
                $name = strtolower($product->name);
                $product->name = ucfirst($name);
                $product->menu = Menu::find($product->menu)['slug'];
                array_push($searched_product, $product);
            }
        } else {
            $searched_product = [];
        }
        $output['data'] = $searched_product;
        // return response()->json($output, 200);
        return response()->json($output)
            ->header('Cache-Control', 'max-age=86400, public');
    }

    public function globleSearch(Request $request)
    {
        $output['res'] = 'success';
        $output['msg'] = 'data retrieved successfully';
        $q = $request->input('q');
        if (!empty($q)) {
            $products = ProductModel::orderBy('entity_id', 'desc')
                ->whereNull('parent_sku')
                ->where('status', 'true')
                ->where(function ($query) use ($q) {
                    $query->where('name', 'like', "$q%")
                        ->orWhere('sku', 'like', "$q%")
                        ->orWhere('metalColor', 'like', "$q%")
                        ->orWhere('diamondQuality', 'like', "$q%")
                        ->orWhere('diamondQuality', 'like', "$q%")
                        ->orWhere('CenterShape', 'like', "$q%")
                        ->orWhere('white_gold_price', 'like', "$q%")
                        ->orWhere('yellow_gold_price', 'like', "$q%")
                        ->orWhere('rose_gold_price', 'like', "$q%")
                        ->orWhere('fractioncomplete', 'like', "$q%")
                        ->orWhere('metalType', 'like', "$q%")
                        ->orWhere('metalWeight', 'like', "$q%")
                        ->orWhere('finishLevel', 'like', "$q%");
                });

            if (!is_null($request->query('shape'))) {
                $shapes = explode(',', strtoupper(trim($request->query('shape'))));
                $products->whereIn('CenterShape', $shapes);
            }

            if (!is_null($request->query('ring_style'))) {
                $subcatSlugs = explode(',', $request->query('ring_style'));

                // Fetch corresponding IDs based on slugs
                $subcatIds = Subcategory::whereIn('slug', $subcatSlugs)->pluck('id')->toArray();

                // If there are IDs, use them in the WHERE clause
                if (!empty($subcatIds)) {
                    $products->where(function ($query) use ($subcatIds) {
                        foreach ($subcatIds as $id) {
                            $query->orWhereRaw("FIND_IN_SET(?, sub_category)", [$id]);
                        }
                    });
                }
            }

            if (!is_null($request->query('metal_color'))) {
                $metalcolor_ids = explode(',', $request->query('metal_color'));
                $products->whereIn('metalColor', $metalcolor_ids);
            }

            $count = $products->count();

            $products = $products->paginate(30);
            $searched_product = [];

            foreach ($products as $product) {
                // $product->name = ucfirst(strtolower($product->name));
                $product->description = ucfirst(strtolower($product->description));
                $product->images = json_decode($product->images);
                $path = parse_url($product->default_image_url, PHP_URL_PATH);
                $extension = pathinfo($path, PATHINFO_EXTENSION);
                ## create image
                $defaulImg = env('AWS_URL') . 'products/images/' . $product->internal_sku . '/' . $product->internal_sku . '.' . $extension;
                $product->default_image_url = $defaulImg;
                $product->videos = json_decode($product->videos);
                $name = strtolower($product->product_browse_pg_name);
                $product->type = $product->type;
                // $product->name = ucfirst($name . ' ' . $product->fractionsemimount);
                $product->name = ucfirst($name);
                $product->menu = Menu::find($product->menu)['slug'];
                $product->white_gold_price = $product->white_gold_price;
                array_push($searched_product, $product);
            }
        } else {
            $searched_product = [];
        }
        $output['product_count'] = isset($count) ? $count : 0;
        $output['data'] = $searched_product;
        // return response()->json($output, 200);
        return response()->json($output)
            ->header('Cache-Control', 'max-age=86400, public');
    }

    public function productStyle(Request $request)
    {
        $output['res'] = 'success';
        $output['msg'] = 'data retrieved successfully';

        $cacheKey = 'ring_style';
        $ring_style = Cache::get($cacheKey);
        if (!$ring_style) {
            $ring_styles = Subcategory::orderBy('order_number', 'asc')->where('menu_id', 7)->where('category_id', 7)->where('status', 'true')->get();
            foreach ($ring_styles as $val) {
                if (!empty($val['image'])) {
                    // $val['image'] = env('AWS_URL').'public/storage/' . $val->image;
                    $val['image'] =  env('AWS_URL') . 'public/' . $val->image;
                }
            }
            Cache::put($cacheKey, $ring_styles, $minutes = 60);
            $output['from'] = 'db';
            $output['data'] = $ring_styles;
            return response()->json($output, 200);
        } else {
            $output['from'] = 'cache';
            $output['data'] = $ring_style;
            return response()->json($output, 200);
        }
    }

    public function getSimilarProducts($ids)
    {
        $products = [];
        $ids = explode(',', $ids);
        foreach ($ids as $product_id) {
            $pro = ProductModel::where('id', $product_id)->first();
            if ($pro) {
                $products[] = $pro;
            }
        }
        return $products;
    }

    ## coveted Products
    public function covetedProducts($menu_name)
    {
        $output['res'] = 'success';
        $output['msg'] = 'data retrieved successfully';
        if ($menu_name == 'engagement-rings') {
            $menu = 7;
            $query = ProductModel::where('menu', $menu)
                ->where('status', 'true')
                ->orwhere('is_newest', 1)
                ->orwhere('is_bestseller', 1)
                ->limit(5)
                ->get();
            foreach ($query as $pro) {
                $path = parse_url($pro->default_image_url, PHP_URL_PATH);
                $extension = pathinfo($path, PATHINFO_EXTENSION);
                ## create image
                $defaulImg = env('AWS_URL') . 'products/images/' . $pro->internal_sku . '/' . $pro->internal_sku . '.' . $extension;
                $pro->default_image_url = $defaulImg;
            }
        } else {
            $menu = 2;
            $query = ProductModel::where('menu', $menu)
                ->where('status', 'true')
                ->where('is_newest', 1)
                ->orwhere('is_bestseller', 1)
                ->limit(5)
                ->get();
            foreach ($query as $pro) {
                $path = parse_url($pro->default_image_url, PHP_URL_PATH);
                $extension = pathinfo($path, PATHINFO_EXTENSION);
                ## create image
                $defaulImg = env('AWS_URL') . 'products/images/' . $pro->internal_sku . '/' . $pro->internal_sku . '.' . $extension;
                $pro->default_image_url = $defaulImg;
            }
        }
        $output['data'] = $query;
        return response()->json($output, 200);
    }
}

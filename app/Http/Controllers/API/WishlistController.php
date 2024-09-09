<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\ProductModel;
use App\Models\ProductPrice;
use App\Models\Wishlist;
use Validator;

class WishlistController extends Controller
{
    public function index(Request $request)
    {

        $rules = [
            'user_id' => 'required|numeric',
            'product_type' => 'required',
            // 'ring_id' => 'required_without_all:diamond_id,gemstone_id',
            // 'ring_type' => 'required_with:ring_id|required_without_all:diamond_id,gemstone_id',
            // 'diamond_id' => 'required_without_all:ring_id,gemstone_id',
            // 'gemstone_id' => 'required_without_all:ring_id,diamond_id',
            // 'ring_color' => 'required_with:ring_id',
            // 'img_sku' => 'required_with:ring_id',
            // 'ring_price' => 'required_with:ring_id',
            // 'engraving' => 'sometimes|required_with:font',
            // 'font' => 'required_with:engraving',
        ];
        $messages = [
            'user_id.required' => 'User id is required.',
            'user_id.numeric' => 'User id must be a numeric value.',
            // 'product_type.required' => 'Product type id is required.',
            // 'ring_id.required_without_all' => 'Ring id is required if no diamond id or gemstone id is provided.',
            // 'diamond_id.required_without_all' => 'Diamond id is required if no ring id or gemstone id is provided.',
            // 'ring_type.required_without_all' => 'Ring id is required',
            // 'gemstone_id.required_without_all' => 'Gemstone id is required if no ring id or diamond id is provided.',
            // 'ring_color.required_with' => 'Ring color is required when ring id is provided.',
            // 'img_sku.required_with' => 'Image SKU is required when ring id is provided.',
            // 'ring_price.required_with' => 'Ring price is required when ring id is provided.',
            // 'engraving.required_with' => 'Engraving is required when font is provided.',
            // 'font.required_with' => 'Font is required when engraving is provided.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $output['res'] = 'error';
            $output['msg'] = $errors;
            return response()->json($output, 401);
        } else {
            $wishlist = new Wishlist;
            $wishlist->product_type = $request->product_type;
            $wishlist->user_id = $request->user_id;
            $wishlist->ring_id = $request->ring_id;
            $wishlist->ring_size = $request->ring_size;
            $wishlist->ring_type = $request->ring_type;
            $wishlist->ring_color = $request->ring_color;
            $wishlist->engraving = $request->engraving;
            $wishlist->font = $request->font;
            $wishlist->ring_price = $request->ring_price;
            $wishlist->ring_carat = $request->ring_carat;
            $wishlist->img_sku = $request->img_sku;
            $wishlist->carat_price = $request->carat_price;
            $wishlist->diamond_id = $request->diamond_id;
            $wishlist->diamond_type = $request->diamond_type;
            $wishlist->diamond_stock_no = $request->diamond_stock_no;
            $wishlist->diamond_price = $request->diamond_price;
            $wishlist->gemstone_id = $request->gemstone_id;
            $wishlist->gemstone_stock_no = $request->gemstone_stock_no;
            $wishlist->gemstone_price = $request->gemstone_price;
            $wishlist->status = 'true';
            $wishlist->is_band_available = isset($request->is_band_available) ? $request->is_band_available : 'false';
            $wishlist->band_sku = $request->band_sku;
            $wishlist->band_price = $request->band_price;
            $wishlist->save();
            $output['res'] = 'success';
            $output['msg'] = 'product added in wishlist';
            $output['data'] = Wishlist::latest()->first();
            return response()->json($output, 200);
        }
    }

    public function getWishlistItem(Request $request)
    {
        // echo env('VDB_AUTH_TOKEN');
        // exit;
        $rules = [
            'user_id' => 'required',
        ];
        $messages = [
            'user_id.required' => 'User id is required.',

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $output['res'] = 'error';
            $output['msg'] = $errors;
            return response()->json($output, 401);
        } else {
            $output['res'] = 'success';
            $output['msg'] = 'Wishlist are ...';
            $cart = Wishlist::orderBy('id', 'desc')->where('user_id', $request->user_id)->where('status', 'true')->get();

            $cart_collection = [];
            foreach ($cart as $cartitems) {
                $item_data = [];
                $item_data['id'] = $cartitems->id;
                $item_data['product_type'] = $cartitems->product_type;
                $item_data['user_id'] = $cartitems->user_id;
                $item_data['ring_id'] = $cartitems->ring_id;
                $item_data['ring_size'] = $cartitems->ring_size;
                $item_data['ring_type'] = $cartitems->ring_type;
                $item_data['active_color'] = $cartitems->ring_color;
                $item_data['engraving'] = $cartitems->engraving;
                $item_data['font'] = $cartitems->font;
                $item_data['ring_carat'] = $cartitems->ring_carat;
                $item_data['carat_price'] = $cartitems->carat_price;
                $item_data['ring_price'] = $cartitems->ring_price;
                $item_data['img_sku'] = $cartitems->img_sku;
                $item_data['diamond_id'] = $cartitems->diamond_id;
                $item_data['diamond_type'] = $cartitems->diamond_type;
                $item_data['diamond_stock_no'] = $cartitems->diamond_stock_no;
                $item_data['diamond_price'] = $cartitems->diamond_price;
                $item_data['gemstone_id'] = $cartitems->gemstone_id;
                $item_data['gemstone_stock_no'] = $cartitems->gemstone_stock_no;
                $item_data['gemstone_price'] = $cartitems->gemstone_price;
                $item_data['is_band_available'] = $cartitems->is_band_available;
                $item_data['band_sku'] = empty($cartitems->band_sku) ? null : $cartitems->band_sku;
                $item_data['band_price'] = $cartitems->band_price;

                if (!is_null($item_data['band_sku']) || !empty($item_data['band_sku'])) {
                    ## if matching set exist then reterive tha details and send them
                    $is_matchingset = ProductModel::where('sku', $item_data['band_sku']);
                    if ($is_matchingset->exists()) {
                        $matching_bands_product = $is_matchingset->first();
                        $matching_bands_product->price = ProductPrice::where('product_sku', $item_data['band_sku'])->where('metalType', '18kt')->where('metalColor', 'White')->where('diamond_type', 'natural')->first()['price'] ?? 0;

                        $item_data['matching_wedding_band'] = $matching_bands_product;
                    } else {
                        $item_data['matching_wedding_band'] = NULL;
                    }
                }

                if (!empty($cartitems->ring_id)) {
                    // fetch ring data here
                    $ring_data = ProductModel::where('id', $cartitems->ring_id)->first();
                    $item_data['ring'] = $ring_data;
                } else {
                    $item_data['ring'] = [];
                }

                if (!empty($cartitems->diamond_id) && !empty($cartitems->diamond_type)) {
                    // fetch diamond data here
                    $diamond_data = '';
                    $encodedDiamondId = $cartitems->diamond_id;
                    // $encodedDiamondId = urlencode($cartitems->diamond_id);
                    $url = "https://apiservices.vdbapp.com/v2/diamonds?type=$cartitems->diamond_type&stock_num=$encodedDiamondId";
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Token token="'.env('VDB_AUTH_TOKEN').'", api_key="' .env('VDB_API_KEY'). '"'
                        ),
                    ));

                    $response = curl_exec($curl);

                    curl_close($curl);
                    $resp = json_decode($response);
                    if ($resp === null && json_last_error() !== JSON_ERROR_NONE) {
                        $item_data['diamond'] = [];
                    } else {
                        // JSON decoding succeeded
                        if (isset($resp->response->body->diamonds)) {
                            // Extract diamond data
                            $diamond_data = $resp->response->body->diamonds;
                            $item_data['diamond'] = $diamond_data;
                        } else {
                            // Handle missing diamonds data
                            $item_data['diamond'] = [];
                        }
                    }

                    // $diamond_data = $resp->response->body->diamonds;
                    // $item_data['diamond'] = $diamond_data;

                } else {
                    $item_data['diamond'] = [];
                }

                if (!empty($cartitems->gemstone_id) || !is_null($cartitems->gemstone_id)) {
                    // fetch gemstone data here
                    $gemstone_data = '';
                    $url = "https://apiservices.vdbapp.com/v2/gemstones?stock_num=$cartitems->gemstone_id";
                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Token token="'.env('VDB_AUTH_TOKEN').'", api_key="' .env('VDB_API_KEY'). '"'
                        ),
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $resp = json_decode($response);
                    $gemstone_data = $resp->response->body->gemstones;
                    $item_data['gemstone'] = $gemstone_data;
                } else {
                    $item_data['gemstone'] = [];
                }
                $cart_collection[] = $item_data;
            }

            $output['data'] = $cart_collection;
            // return response()->json($output, 200);
            // return response()->json($output)
            // ->header('Cache-Control', 'max-age=86400, public');
            return response()->json($output)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }
    }

    public function deleteItem($id)
    {
        $obj = Wishlist::find($id);
        if ($obj) {
            $Item = $obj->delete();
            if ($Item) {
                $output['res'] = 'success';
                $output['msg'] = 'Item removed from wishlist';
                $output['data'] = '';
                return response()->json($output, 200);
            } else {
                $output['res'] = 'error';
                $output['msg'] = 'something went wrong while deleting';
                $output['data'] = '';
                return response()->json($output, 201);
            }
        } else {
            $output['res'] = 'error';
            $output['msg'] = 'Item not available for this ID.';
            $output['data'] = '';
            return response()->json($output, 201);
        }
    }


    public function checkProductInWishlist(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'product_type' => 'required',
            'product_id' => 'required'

        ];
        $messages = [
            'user_id.required' => 'User id is required.',
            'product_type.required' => 'Product type is required.',
            'product_id.required' => 'Product id is required.',

        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $output['res'] = 'error';
            $output['msg'] = $errors;
            return response()->json($output, 401);
        } else {
            $action = $request->product_type;
            switch ($action) {
                case "ring":
                    $wishlistExists =  Wishlist::where('user_id', $request->user_id)
                        ->where('ring_id', $request->product_id)
                        ->exists();

                    if ($wishlistExists) {
                        $wishlistItemId = Wishlist::where('user_id', $request->user_id)
                            ->where('ring_id', $request->product_id)
                            ->value('id');
                        return $wishlistItemId;
                    } else {
                        return null;
                    }
                    break;
                case "gemstone":
                    $wishlistExists =  Wishlist::where('user_id', $request->user_id)
                        ->where('gemstone_id', $request->product_id)
                        ->exists();
                    if ($wishlistExists) {
                        $wishlistItemId = Wishlist::where('user_id', $request->user_id)
                            ->where('gemstone_id', $request->product_id)
                            ->value('id');
                        return $wishlistItemId;
                    } else {
                        return null;
                    }
                    break;
                case "diamond":
                    $wishlistExists =  Wishlist::where('user_id', $request->user_id)
                        ->where('diamond_id', $request->product_id)
                        ->exists();
                    if ($wishlistExists) {
                        $wishlistItemId = Wishlist::where('user_id', $request->user_id)
                            ->where('gemstone_id', $request->product_id)
                            ->value('id');
                        return $wishlistItemId;
                    } else {
                        return null;
                    }
                    break;
            }
        }
    }
}

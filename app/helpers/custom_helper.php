<?php

use Illuminate\Support\Facades\DB;
use App\Models\ProductModel;
use App\Models\ProductImageModel;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\OrderItem;
use App\Models\MetalColor;
use App\Models\RingMetal;
use App\Models\CenterStone;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

if (!function_exists('getProductNname')) {
    function getProductNname($id)
    {
        $pro = ProductModel::findOrFail($id);
        if($pro != null)
        {
            return !empty($pro->product_browse_pg_name) && !is_null($pro->product_browse_pg_name)?$pro->product_browse_pg_name:$pro->name;
        }
        return null;
    }
}

if (!function_exists('getProductIdBasedOnSku')) {
    function getProductIdBasedOnSku($sku)
    {
        $id = ProductModel::where('sku', $sku)->pluck('id')->first() ?? null;
        return $id;
    }
}

if (!function_exists('getCenterStoneValues')) {
    function getCenterStoneValues($id)
    {
        $menu =  CenterStone::find($id);
        return $menu ?? '';
    }
}

if (!function_exists('getMenu')) {
    function getMenu($menu_id)
    {
        $menu =  Menu::find($menu_id);
        return $menu->name ?? '';
    }
}


if (!function_exists('getCategories')) {
    function getCategories($cat_ids)
    {
        $cat = explode(',', $cat_ids);
        // if(!empty($cat) || $cat = '')
        // {
        $cat = array_filter($cat);
        $arr = [];
        foreach ($cat as $catdata) {
            $value = Category::find($catdata);
            array_push($arr, $value->name);
        }
        return $values = implode(',', $arr);
        // }else
        // {
        //     $values = '';
        //     return $values;
        // }
    }
}

if (!function_exists('getSubCategories')) {
    function getSubCategories($subcat_ids)
    {
        $subcat = explode(',', $subcat_ids);
        $arr = [];
        foreach ($subcat as $subcatdata) {
            $value = Subcategory::find($subcatdata);
            array_push($arr, $value->name ?? null);
        }
        return implode(',', $arr);
    }
}


if (!function_exists('getProductImages')) {
    function getProductImages($id, $color)
    {
        $product =  ProductModel::where('id', $id)->first();
        if (!isset($product->default_image_url)) {
            return null;
        }
        $imageovermounting = $product->default_image_url;

        $extension = pathinfo($imageovermounting, PATHINFO_EXTENSION);
        $image =  env('AWS_URL') . 'products/images/' . $product->internal_sku . '/' . $product->internal_sku . '.' . $extension;
        $img = explode('.' . $extension, $image);
        switch ($color) {
            case '18K WHITE GOLD':
                $image = $image;
                break;
            case '18K YELLOW GOLD':
                $color = 'alt';
                $image = $img[0] . '.' . $color . '.' . $extension;
                break;
            case '18K ROSE GOLD':
                $color = 'alt1';
                $image =  $img[0] . '.' . $color . '.' . $extension;
                break;
            case '18k-white-gold':
                $image = $image;
                break;
            case '18k-yellow-gold':
                $color = 'alt';
                $image = $img[0] . '.' . $color . '.' . $extension;
                break;
            case '18k-rose-gold':
                $color = 'alt1';
                $image =  $img[0] . '.' . $color . '.' . $extension;
                break;
        }
        return  $image;
    }
}



if (!function_exists('getDiamondImages')) {
    function getDiamondImages($stock_number, $type)
    {
        if ($stock_number != null) {
            try {
                $cacheKey = 'get_diamond_image';
                $diamond_image = Cache::get($cacheKey);
                if (!$diamond_image) {
                    $api_url = "https://apiservices.vdbapp.com/v2/diamonds?type=" . $type . "&stock_num=" . $stock_number;
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $api_url,
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'GET',
                        CURLOPT_HTTPHEADER => array(
                            'Authorization: Token token="' . env('VDB_AUTH_TOKEN') . '", api_key="' . env('VDB_API_KEY') . '"'
                        ),
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $jsn_data = json_decode($response);
                    // Check if the response is successful and contains diamonds
                    if (isset($jsn_data->response->body->diamonds[0])) {
                        $response = $jsn_data->response->body->diamonds[0];
                        Cache::put($cacheKey, $diamond_image, $minutes = 60);
                        return $response;
                    } else {
                        return null;
                    }
                }





                // return $jsn_data->response->body->diamonds[0];
            } catch (Exception $e) {
                return null;
            }
        }
    }
}


if (!function_exists('getGemStoneImages')) {
    function getGemStoneImages($stock_number)
    {
        if ($stock_number != null) {
            try {
                $api_url = "https://apiservices.vdbapp.com/v2/gemstones?markup_mode=true&stock_num=" . $stock_number;
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $api_url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Token token="' . env('VDB_AUTH_TOKEN') . '", api_key="' . env('VDB_API_KEY') . '"'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                $jsn_data = json_decode($response);

                // Check if the response is successful and contains diamonds
                if (isset($jsn_data->response->body->gemstones[0])) {
                    return $jsn_data->response->body->gemstones[0];
                } else {
                    return null;
                }
            } catch (Exception $e) {
                return null;
            }
        }
    }
}

if (!function_exists('getOrderItem')) {
    function getOrderItem($order_id)
    {
        return OrderItem::where('order_id', $order_id)->get();
    }
}

if (!function_exists('getRingName')) {
    function getRingName($ring_id)
    {
        $data = ProductModel::find($ring_id);
        if ($data) {
            if (!empty($data->product_browse_pg_name)) {
                $name = $data->product_browse_pg_name;
            } else {
                $name = $data->name;
            }
            return ucfirst(strtolower($name));
        }
    }
}


if (!function_exists('getMetalColorByID')) {
    function getMetalColorByID($id)
    {
        $data = MetalColor::find($id);
        if ($data) {
            if (!empty($data->name)) {
                $name = $data->name;
            } else {
                $name = $data->name;
            }
            return ucfirst(strtolower($name));
        }
    }
}

if (!function_exists('getMetalTypeByID')) {
    function getMetalTypeByID($id)
    {
        $data = RingMetal::find($id);
        if ($data) {
            if (!empty($data->metal)) {
                $metalType = $data->metal;
            } else {
                $metalType = $data->name;
            }
            return ucfirst(strtolower($metalType));
        }
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Widget;
use App\Models\Cmscategory;
use Illuminate\Support\Facades\Cache;

class MenuController extends Controller
{

    public function index()
    {
        $output['res'] = 'success';
        $output['msg'] = 'data retrieved successfully';

        $cacheKey = 'menu_list';
        // $menu_list = Cache::get($cacheKey);
        // $menu_list = Cache::forget($cacheKey);
        // if (!$menu_list) {
        $menus = Menu::orderBy('order_number', 'asc')->where('status', 'true')->get();
        foreach ($menus as $menu) {
            $cat = Category::orderBy('order_number', 'asc')->where('status', 'true')->where('menu', $menu->id)->get();
            $menu['categories'] = $cat;
            foreach ($menu['categories'] as $menucat) {
                $subcat = Subcategory::orderBy('order_number', 'asc')
                    ->where('status', 'true')
                    ->where('menu_id', $menucat->menu)
                    ->where('category_id', $menucat->id)
                    ->get();
                $subcategory_collection = [];
                foreach ($subcat as $subcategory) {
                    if (($subcategory->image != null)) {
                        if ($subcategory->img_status == 'true') {
                            $subcategory->image = env('AWS_URL') . 'public/storage/' . $subcategory->image;
                        } else {
                            $subcategory->image = '';
                        }
                    } else {
                        $subcategory->image = '';
                    }
                    array_push($subcategory_collection, $subcategory);
                }
                $menucat['subcategories'] = $subcategory_collection;
            }
        }
        // Cache::put($cacheKey, $menus, $minutes = 60);
        $output['data'] = $menus;
        $output['from'] = 'db';
        return response()->json($output, 200);
        // } else {
        //     $output['data'] = $menu_list;
        //     $output['from'] = 'cache';
        //     return response()->json($output, 200);
        // }
    }


    public function brand()
    {
        $output['res'] = 'success';
        $output['msg'] = 'data retrieved successfully';
        $cacheKey = 'menu_brand';
        $menu_brand = Cache::get($cacheKey);
        if (!$menu_brand) {
            $brand = Widget::where('name', 'BRAND')->first();
            $output['data'] = $brand;
            $output['from'] = 'db';
            Cache::put($cacheKey, $brand, $minutes = 120);
            return response()->json($output, 200);
        } else {
            $output['data'] = $menu_brand;
            $output['from'] = 'cache';
            return response()->json($output, 200);
        }




        // return response()->json($output, 200);
    }

    public function getMenuName($slug)
    {
        if (!is_null($slug)) {
            $output['res'] = 'success';
            $output['msg'] = 'data retrieved successfully';
            $data = Menu::where('slug', $slug)->first();
            $output['name'] = $data['name'];
            return response()->json($output, 200);
        }
    }

    public function rings(Request $request)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://www.overnightmountings.com/api/rest/itembom',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: PHPSESSID=e72b989033c638afeafefce1a81c066a; frontend=01e615915edbe48fb3853d6e606864be; frontend_cid=ndgBBtdWKeEuSQHW'
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    ## get meta data
    public function check(Request $request)
    {
        if (empty($request->subcategory) && empty($request->category) && !empty($request->menu)) {
            $metadata =  Menu::where('slug', $request->menu)->orWhere('alias', $request->menu)->first();
        }
        if (!empty($request->menu) && !empty($request->category) && empty($request->subcategory)) {
            $menu_id =  Menu::where('slug', $request->menu)->first()['id'];
            $metadata = Category::where('menu', $menu_id)->where('slug', $request->category)->first();
        }
        if (!empty($request->menu) && !empty($request->category) && !empty($request->subcategory)) {
            $menu_id =  Menu::where('slug', $request->menu)->first()['id'];
            $cat_id =  Category::where('menu', $menu_id)->where('slug', $request->category)->orWhere('alias', $request->category)->first()['id'];
            $metadata = Subcategory::where('menu_id', $menu_id)->where('category_id', $cat_id)->where('slug', $request->subcategory)->orWhere('alias', $request->subcategory)->first();
        }

        if (!empty($request->menu) && empty($request->category) && !empty($request->subcategory)) {

            $menu_id =  Menu::where('slug', $request->menu)->orWhere('alias', $request->menu)->first()['id'];
            $metadata = Subcategory::where('menu_id', $menu_id)->where('slug', $request->subcategory)->orWhere('alias', $request->subcategory)->first();
        }
        $output['res'] = 'success';
        $output['msg'] = 'data retrieved successfully';
        $output['data'] = $metadata;
        // return response()->json($output, 200);
        return response()->json($output)
        ->header('Cache-Control', 'max-age=86400, public');
    }

    ## get meta data
    public function cmsMetaData(Request $request)
    {
        if (!is_null($request->route)) {
            $metadata =  Cmscategory::where('name', $request->route)->first();
            $output['res'] = 'success';
            $output['msg'] = 'data retrieved successfully';
            $output['data'] = $metadata;
            return response()->json($output, 200);
        } else {
            $output['res'] = 'error';
            $output['msg'] = 'Route name is required';
            $output['data'] = [];
            return response()->json($output, 401);
        }
    }
}

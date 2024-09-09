<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteInfo;
use App\Models\HomeContent;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\MetalColor;
use App\Models\Widget;
use App\Models\HomeSection1;
use App\Models\HomeSection2;
use App\Models\HomeSection3;
use App\Models\HomeSection4;
use App\Models\HomeSection5;
use App\Models\HomeSection6;
use App\Models\Banner;
use App\Models\ShopByCategoryHomePage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class SiteinfoController extends Controller
{
    public function index()
    {
        $output['res'] = 'success';
        $output['msg'] = 'data retrieved successfully';

        $data = SiteInfo::first();
        $data->logo = env('AWS_URL') . 'public/storage/' . $data->logo;
        $data->favicon =  env('AWS_URL') . 'public/storage/' . $data->favicon;
        $output['data'] = $data;
        // return response()->json($output, 200);
        return response()->json($output)
        ->header('Cache-Control', 'max-age=86400, public');
    }

    public function homeContent()
    {
        $output['res'] = 'success';
        $output['msg'] = 'data retrieved successfully';
        $cacheKey = 'site_info';
        $siteinfo = Cache::get($cacheKey);
        // $siteinfo = Cache::forget($cacheKey);
        // exit;
        if (!$siteinfo) {
            $collection = [];
            $section1 =  HomeSection1::first();
            $section1['image'] =  env('AWS_URL') . 'public/' . $section1['image'];
            $collection['section1'] = $section1;

            $section2 =  HomeSection2::first();
            $section2['image'] =  env('AWS_URL') . 'public/' . $section2['image'];
            $collection['section2'] = $section2;

            $section3 =  HomeSection3::first();
            $section3['image'] =  env('AWS_URL') . 'public/' . $section3['image'];
            $collection['section3'] = $section3;


            $section4 =  HomeSection4::first();
            $section4['image1'] =  env('AWS_URL') . 'public/' . $section4['image1'];
            $section4['image2'] =  env('AWS_URL') . 'public/' . $section4['image2'];
            $collection['section4'] = $section4;

            $section5 =  HomeSection5::first();
            $section5['image_desktop'] =  env('AWS_URL') . 'public/' . $section5['image_desktop'];
            $section5['image_mobile'] =  env('AWS_URL') . 'public/' . $section5['image_mobile'];
            $collection['section5'] = $section5;

            $section6 =  HomeSection6::first();
            $section6['image1'] =  env('AWS_URL') . 'public/' . $section6['image1'];
            $section6['image2'] =  env('AWS_URL') . 'public/' . $section6['image2'];
            $collection['section6'] = $section6;

            $shopbycat = [];
            $q = ShopByCategoryHomePage::orderBy('order_number', 'asc')->where('status', 'true');
            if ($q->exists()) {
                foreach ($q->get() as $sp) {
                    $sp->image = env('AWS_URL') . 'public/' . $sp->image;
                    $shopbycat[] = $sp;
                }
            }

            $collection['shopbycategory'] =$shopbycat;
                Cache::put($cacheKey, $collection, $minutes = 60);
            // Add the data to output if needed
            $output['data'] = $collection;
            $output['check_from'] = 'from db';
        } else {
            $output['data'] = $siteinfo;
            $output['check_from'] = 'from cache';
        }
        // Return JSON response with output
        // return response()->json($output, 200);
        return response()->json($output)
        ->header('Cache-Control', 'max-age=86400, public');
    }



    public function metalColor()
    {

        $output['res'] = 'success';
        $output['msg'] = 'data retrieved successfully';
        $cacheKey = 'metal_color';
        $metalColor  = Cache::get($cacheKey);
        if (!$metalColor) {
            $data =  MetalColor::orderBy('id', 'asc')->where('status', 'true')->get();
            Cache::put($cacheKey, $data, $minutes = 120);
            $output['from'] = 'db';
            $output['data'] = $data;
            return response()->json($output, 200);
        } else {
            $output['from'] = 'cache';
            $output['data'] = $metalColor;
            return response()->json($output, 200);
        }
    }

    public function otherHomeData()
    {
        $output['res'] = 'success';
        $output['msg'] = 'data retrieved successfully';
        $collection = [];
        $collection['ready_to_ship_rings'] = Widget::where('name', 'Ready to ship rings')->first();
        $collection['lab_diamond_ring'] = Widget::where('name', 'Lab diamond rings')->first();
        $collection['three_stone_rings'] = Widget::where('name', 'Three stone rings')->first();
        $collection['nature_inspired_rings'] = Widget::where('name', 'Nature inspired rings')->first();
        $collection['hidden_Halo_rings'] = Widget::where('name', 'Hidden Halo rings')->first();
        $collection['Bridal_sets'] = Widget::where('name', 'Bridal sets')->first();
        $collection['classic_rings'] = Widget::where('name', 'Classic rings')->first();

        $output['data'] = $collection;
        // return response()->json($output, 200);
        return response()->json($output)
        ->header('Cache-Control', 'max-age=86400, public');
    }

     ## get meta data
     public function getMetaData(Request $request)
     {
         if(is_null($request->subcategory) && is_null($request->category) && !is_null($request->menu))
         {
            $metadata =  Menu::where('slug',$request->menu)->first();
         }
         if(!is_null($request->menu) && !is_null($request->category) && is_null($request->subcategory) )
         {
            $menu_id =  Menu::where('slug',$request->menu)->first()['id'];
            $metadata = Category::where('menu',$menu_id)->where('slug',$request->category)->first();
         }
         if(!is_null($request->menu) && !is_null($request->category) && !is_null($request->subcategory) )
         {
            $menu_id =  Menu::where('slug',$request->menu)->first()['id'];
            $cat_id =  Category::where('menu',$menu_id)->where('slug',$request->category)->orWhere('alias',$request->category)->first()['id'];
            $metadata = Subcategory::where('menu_id',$menu_id)->where('category_id',$cat_id)->where('slug',$request->subcategory)->orWhere('alias',$request->subcategory)->first();
         }

         if(!is_null($request->menu) && is_null($request->category) && !is_null($request->subcategory) )
         {
            $menu_id =  Menu::where('slug',$request->menu)->first()['id'];
            $metadata = Subcategory::where('menu_id',$menu_id)->where('slug',$request->subcategory)->orWhere('alias',$request->subcategory)->first();
         }
         $output['res'] = 'success';
         $output['msg'] = 'data retrieved successfully';
         $output['data'] = $metadata;
         return response()->json($output, 200);
     }

}

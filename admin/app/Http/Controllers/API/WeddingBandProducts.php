<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductModel;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\DiamondShape;
use App\Models\ProductPrice;
use App\Models\CenterStone;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use PhpParser\Node\Stmt\Else_;

class WeddingBandProducts extends Controller
{
    // public function index(Request $request)
    // {
    //     $category_slug = $request->query('category');
    //     if(!empty($category_slug))
    //     {
    //        $cat_id = Category::where('slug',$category_slug)->first()['id'];
    //     }
    //     $subcategory_slug = $request->query('subcategory');
    //     if(!empty($category_slug) && !empty($subcategory_slug))
    //     {
    //         $subcat_id = Subcategory::where('category_id',$cat_id)->where('slug',$subcategory_slug)->first()['id'];
    //     }



    //     $query = ProductModel::where('menu', 2)
    //         ->where('status', 'true')
    //         ->whereNull('parent_sku');
    //         if(isset($cat_id) && isset($subcat_id))
    //         {
    //          $query->where('category',$cat_id)->where('sub_category');
    //         }



    //     $count =  $query->count();
    //     $productList = $query->paginate(30);
    //     $output['count'] = $count;
    //     $output['data'] = $productList;
    //     return response()->json($output, 200);
    // }
    public function index(Request $request)
    {
        $category_slug = $request->query('category');
        $subcategory_slug = $request->query('subcategory');

        $cat_id = null;
        $subcat_id = null;

        if (!empty($category_slug)) {
            $cat_id = Category::where('slug', $category_slug)->pluck('id')->first();
        }

        if (!empty($category_slug) && !empty($subcategory_slug)) {
            $subcat_id = Subcategory::where('category_id', $cat_id)
                ->where('slug', $subcategory_slug)
                ->pluck('id')
                ->first();
        }

        $query = ProductModel::where('menu', 2)
            ->where('status', 'true')
            ->whereNull('parent_sku');

        if (isset($cat_id)) {
            $query->where('category', $cat_id);
        }

        if (isset($subcat_id)) {
            $query->where('sub_category', $subcat_id);
        }

        $count = $query->count();
        $productList = $query->paginate(30);

        $output['count'] = $count;
        $output['data'] = $productList;

        return response()->json($output, 200);
    }
}

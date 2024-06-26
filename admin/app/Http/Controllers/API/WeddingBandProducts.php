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
    //     $subcategory_slug = $request->query('subcategory');

    //     $cat_id = null;
    //     $subcat_id = null;

    //     if (!empty($category_slug)) {
    //         $cat_id = Category::where('slug', $category_slug)->pluck('id')->first();
    //     }

    //     if (!empty($category_slug) && !empty($subcategory_slug)) {
    //         $subcat_id = Subcategory::where('category_id', $cat_id)
    //             ->where('slug', $subcategory_slug)
    //             ->pluck('id')
    //             ->first();
    //     }


    //     $query = ProductModel::where('menu', 2)
    //         ->where('status', 'true')
    //         ->whereNull('parent_sku');

    //     if (isset($cat_id)) {
    //         $query->where('category', $cat_id);
    //     }

    //     if (isset($subcat_id)) {
    //         $query->where('sub_category', $subcat_id);
    //     }

    //     if (!is_null($request->query('metal_color'))) {
    //         $metalcolor_id = $request->query('metal_color');
    //         $query->where('metalColor_id', $metalcolor_id);
    //     }


    //     if (!is_null($request->query('price_range'))) {
    //         $range = explode(',', $request->query('price_range'));
    //         $min = $range[0];
    //         $max = $range[1];
    //         $query->whereBetween('product_price.price', [$min, $max]);
    //     }

    //     // Apply sorting based on request
    //     if (!is_null($request->query('sortby'))) {
    //         $sortBy = $request->query('sortby');

    //         if ($sortBy == 'Newest') {
    //             $query->orderBy('created_at', 'desc');
    //         } elseif ($sortBy == 'best_seller') {
    //             $query->where('is_bestseller', '1');
    //         }
    //     }


    //     $count = $query->count();
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

        if ($cat_id) {
            $query->where('category', $cat_id);
        }

        if ($subcat_id) {
            $query->where('sub_category', $subcat_id);
        }

        if ($request->query('metal_color')) {
            $metalcolor_id = $request->query('metal_color');
            $query->where('metalColor_id', $metalcolor_id);
        }

        if ($request->query('price_range')) {
            $range = explode(',', $request->query('price_range'));
            if (count($range) == 2) {
                $min = $range[0];
                $max = $range[1];
                $query->whereBetween('product_price.price', [$min, $max]);
            }
        }

        // Apply sorting based on request
        if ($request->query('sortby')) {
            $sortBy = $request->query('sortby');

            if ($sortBy == 'Newest') {
                $query->orderBy('created_at', 'desc');
            } elseif ($sortBy == 'best_seller') {
                $query->where('is_bestseller', '1');
            }
        }

        $count = $query->count();
        $productList = $query->paginate(30);

        $output['count'] = $count;
        $output['data'] = $productList;

        return response()->json($output, 200);
    }
}

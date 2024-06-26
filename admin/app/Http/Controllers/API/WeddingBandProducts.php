<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductModel;
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
    public function index(Request $request)
    {
        $query = ProductModel::where('menu', 2)
            ->where('status', 'true')
            ->whereNull('parent_sku');

        $count =  $query->count();
        $productList = $query->paginate(30);
        $output['count'] = $count;
        $output['data'] = $productList;
        return response()->json($output, 200);
    }
}

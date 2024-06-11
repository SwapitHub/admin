<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\UpsShipping;
use App\Models\ShipmentModel;
use App\Models\OrderModel;
use Validator;

class ShippingController extends Controller
{
    public function index(Request $request , $order_id)
    {
        $orders = OrderModel::findOrFail($order_id);
        if($orders)
        {

        }
    }
}

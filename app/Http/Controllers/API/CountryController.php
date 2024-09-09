<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\OvernightShippingCharge;
use Validator;

class CountryController extends Controller
{
    public function fetchOvernightShippingCharge()
    {
        $OvernightShippingCharge =  OvernightShippingCharge::first();
        $output['res'] = 'success';
        $output['msg'] = 'data retrieved successfully';
        $output['data'] = $OvernightShippingCharge;
        return response()->json($output, 200)->header('Cache-Control', 'no-cache');
    }
    public function index()
    {
        $query =  Country::orderBy('order_number', 'asc')->where('status', 'true')->select('id', 'name', 'status', 'order_number');
        if ($query->exists()) {
            $list = $query->get();
            $output['res'] = 'success';
            $output['msg'] = 'data retrieved successfully';
            $output['data'] = $list;
            return response()->json($output, 200)->header('Cache-Control', 'no-cache');
        } else {
            $output['res'] = 'error';
            $output['msg'] = 'Country not available.';
            $output['data'] = [];
            return response()->json($output, 401);
        }
    }

    public function fetchStateBasedOnCountry(Request $request)
    {
        $rules = [
            'country_id' => 'required|numeric',
        ];
        $messages = [
            'country_id.required' => 'Country id is required.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $output['res'] = 'error';
            $output['msg'] = $errors;
            return response()->json($output, 401);
        } else {
            $query = State::orderBy('order_number', 'asc')->where('country_id', $request->country_id)->where('status', 'true');
            if (!$query->exists()) {
                $output['res'] = 'error';
                $output['msg'] = 'State not available for slected country.';
                $output['data'] = [];
                return response()->json($output, 401);
            } else {
                $output['res'] = 'success';
                $output['msg'] = 'States are ..';
                $output['data'] = $query->get();
                return response()->json($output, 200);
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OvernightShippingCharge;

class OvernighShippingChargeController extends Controller
{
    public function index()
    {
        $data['obj'] = OvernightShippingCharge::first();
        $data['title'] = 'Overnight shipping charge';
        $data['backtrack'] = 'Shipping charge';
        $data['url_action'] = route('overnight.shipping.charge');
        return view('admin.overnight-shipping', $data);
    }
    public function update(Request $request)
    {
        $this->validate($request, [
            'charge' => 'required|numeric',
        ], [
            'charge.required' => 'The Charge amount is required'
        ]);
        $obj = OvernightShippingCharge::first();
        if ($obj) {
            $obj->charge = $request->charge;
            $obj->save();
            return redirect()->back()->with('success', 'Data update successfully');
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\UpsShipping;
use App\Models\ShipmentModel;
use App\Models\OrderModel;
use App\Models\AddresModel;
use Validator;

class ShippingController extends Controller
{
    public function index(Request $request , $order_id)
    {
        $orders = OrderModel::findOrFail($order_id);
        if($orders)
        {
            $addressCount  = explode(',',$orders['address']);
            if(count($addressCount) > 1)
            {
                  // if there is two address shipping and billing address
            }
            else
            {
                $addressData = AddresModel::find($orders['address']);
                $payload = [
                    'FirstName'=>$addressData['first_name'],
                    'LastName'=>$addressData['last_name'],
                    'StreetAddress'=>'123 Jill Ave',
                    // 'StreetAddress'=>$addressData['address_line1'],
                    // 'City'=>$addressData['city'],
                    'City'=>'CYPRESS',
                    'State'=>'CA',
                    'Country'=>'US',
                    'Zip'=>'90630',
                    'TelephoneNo'=>'7145555871',
                    'Email'=>$addressData['email'],
                ];
                $shipment = new UpsShipping();
                $response = $shipment->createQuote($payload);
                if($response){
                  $values =  $shipment->createShipping($response);
                  var_dump($values);
                }

            }


        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\UpsShipping;
use App\Models\ShipmentModel;
use App\Models\OrderModel;
use App\Models\AddresModel;
use App\Models\TransactionModel;
use Validator;

class ShippingController extends Controller
{
    public function index(Request $request, $order_id)
    {
        $orders = OrderModel::findOrFail($order_id);
        if ($orders) {
            $addressCount  = explode(',', $orders['address']);
            if (count($addressCount) > 1) {
                // if there is two address shipping and billing address
            } else {
                $addressData = AddresModel::find($orders['address']);
                $payload = [
                    'FirstName' => $addressData['first_name'],
                    'LastName' => $addressData['last_name'],
                    'StreetAddress' => '123 Jill Ave',
                    // 'StreetAddress'=>$addressData['address_line1'],
                    // 'City'=>$addressData['city'],
                    'City' => 'CYPRESS',
                    'State' => 'CA',
                    'Country' => 'US',
                    'Zip' => '90630',
                    'TelephoneNo' => '7145555871',
                    'Email' => $addressData['email'],
                ];
                $shipment = new UpsShipping();
                $response = $shipment->createQuote($payload);
                if ($response) {
                    $values =  $shipment->createShipping($response);
                    //   var_dump($values);
                    if ($values['res'] == 'success') {
                        $result = $values['data'];
                        $updateOrder = ['tracking_number' => $result['TrackingNumber'],'status'=>'COMPLETED'];
                        //  $updateSuccess = OrderModel::update($order_id, $updateOrder);
                        $uporder = OrderModel::find($order_id);
                        $updateSuccess = $uporder->update($updateOrder);
                        if ($updateSuccess) {
                            ## make shipment add data in shipment table
                            //find transactionid
                            $transaction = TransactionModel::where('order_id',$orders['order_id'])->first();
                            $shipdata = new ShipmentModel();
                            $shipdata->order_id = $orders['order_id'];
                            $shipdata->transaction_id = $transaction['transaction_id'];
                            $shipdata->status = 'COMPLETED';
                            $shipdata->amount = $orders['amount'];
                            $shipdata->save();
                            return redirect()->back()->with('success', 'Shipment created successfully');
                        } else {
                            return redirect()->back()->with('error', 'API error');
                        }
                    }
                }
            }
        }
    }
}

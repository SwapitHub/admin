<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\UpsShipping;
use App\Models\ShipmentModel;
use App\Models\OrderModel;
use App\Models\AddresModel;
use App\Models\TransactionModel;
use App\Models\OrderStatus;
use App\Models\User;
use App\Models\InvoiceModel;
use Illuminate\Support\Facades\DB;
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
                        $json_data = $values['json_data'];

                        $updateOrder = ['tracking_number' => $result['TrackingNumber'], 'status' => 'COMPLETED', 'order_status' => 3];
                        $uporder = OrderModel::find($order_id);
                        $updateSuccess = $uporder->update($updateOrder);
                        if ($updateSuccess) {
                            ## make shipment add data in shipment table
                            //find transactionid
                            $transaction = TransactionModel::where('order_id', $orders['order_id'])->first();
                            $shipdata = new ShipmentModel();
                            $shipdata->order_id = $orders['order_id'];
                            $shipdata->transaction_id = $transaction['transaction_id'];
                            $shipdata->ShipmentId = $json_data['ShipmentId'];
                            $shipdata->status = 'COMPLETED';
                            $shipdata->delivery_status = 3;
                            $shipdata->json_data = json_encode($json_data);
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

    ## shipment list
    public function list()
    {
        $collection = [];
        $list = ShipmentModel::select('shipments.*', 'order_status.name') // Adjust the select clause as needed
            ->join('order_status', 'shipments.delivery_status', '=', 'order_status.id')
            ->orderBy('shipments.id', 'desc')
            ->get();

        foreach ($list as $item) {
            $order_id = $item['order_id'];
            $order_data = OrderModel::where('order_id', $order_id)->first();
            $users = User::where('id', $order_data['user_id'])->first();
            $address = AddresModel::where('user_id', $order_data['user_id'])->first();
            $item['tracking_number'] = $order_data['tracking_number'];
            $item['username'] = $users['first_name'] . ' ' . $users['last_name'];
            $item['useraddress'] = $address;
            $collection[] = $item;
        }
        $data = [
            'list' => $collection,
            'order_status' => OrderStatus::where('status', 'true')->get(),
        ];
        return view('admin.shipments', $data);
    }

    ##shipment details
    public function shipmentDetail($id)
    {
        $order_data = DB::table('orders')
            ->where('orders.id', $id)
            ->orWhere('orders.order_id', $id)
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('users.first_name', 'users.first_name', 'users.last_name', 'users.last_name', 'users.email', 'users.email', 'orders.*')
            ->first();
        $address_ = explode(',', $order_data->address);
        $address_collection = [];
        foreach ($address_ as $address) {
            $address_collection[] = AddresModel::where('id', $address)->first();
        }
        if ($address_collection[0] == NULL) {
            $address_collection = [];
        }

        $data['address'] = $address_collection;
        $data['order'] = $order_data;
        $invoiceData = InvoiceModel::where('order_id', $order_data->order_id);
        $data['invoice_count'] = $invoiceData->count();
        $data['invoice'] = $invoiceData->first();
        $data['shipping_data'] = ShipmentModel::where('order_id', $order_data->order_id)->first();
        return view('admin.shipment-view', $data);
    }

    ## update order and shipping status

    public function updateStatus(Request $request)
    {
        $this->validate($request, [
            'id' => 'required',
            'order_status' => 'required',
        ], [
            'id.required' => 'The Id field is required.',
            'order_status.required' => 'The Order status field is required.',
        ]);

        $shipment = ShipmentModel::find($request->id);

        if (!$shipment) {
            return redirect()->back()->with('error', 'Shipment not found.');
        }

        $is_update = $shipment->update(['delivery_status' => $request->order_status]);

        if ($is_update) {
            OrderModel::where('order_id', $shipment->order_id)->update(['order_status' => $request->order_status]);
        }
        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
}

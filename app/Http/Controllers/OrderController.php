<?php

namespace App\Http\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItem;
use App\Models\TransactionModel;
use App\Models\InvoiceModel;
use App\Models\Cart;
use App\Models\User;
use App\Models\AddresModel;
use App\Models\OrderStatus;
use App\Models\RefundModel;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Cache;
use App\Exports\OrderExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Library\Clover;

use Illuminate\Http\Request;

class OrderController extends Controller
{

    ##order refund view
    public function refund()
    {
        // $data = [];
        $query = DB::table('refund')
            ->join('users', 'refund.user_id', '=', 'users.id')
            ->join('orders', 'refund.order_id', '=', 'orders.order_id')
            ->select('users.first_name', 'users.last_name', 'users.email', 'refund.*','orders.created_at as order_created_at')
            ->orderBy('refund.id', 'desc')
            ->paginate(10);

        $data = ['list' => $query];
        return view('admin.refundlist', $data);
    }

    ############### order status secion
    public function orderStatus()
    {


        $data = [
            "title" => 'Order status list ',
            "viewurl" => 'order.status.add',
            "editurl" => 'order.status.edit',
            'list' => OrderStatus::orderBy('id', 'desc')->where('status', 'true')->get(),
        ];
        return view('admin.order_status', $data);
    }

    public function addOrderStatus(Request $request)
    {
        $data = [
            'url_action' => route('order.status.postadd'),
            'backtrack' => 'order.status',
            'title' => 'Add Order Status',
            "obj" => '',
        ];
        return view('admin.orderStatus', $data);
    }

    public function postAddOrderStatus(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ], [
            'name.required' => 'The name field is required.',
        ]);
        $status = new OrderStatus();
        $status->name = $request->name;
        $status->status = $request->status ?? 'false';
        $status->save();
        return redirect()->back()->with('success', 'Order status added successfully');
    }

    public function editOrderStatus($id)
    {
        $editdata = OrderStatus::find($id);
        if ($editdata == null) {
            return 'no data';
        }
        $data = [
            'url_action' => route('order.status.update', ['id' => $editdata['id']]),
            'backtrack' => 'order.status',
            'title' => 'Edit Order Status',
            'obj' => $editdata,
        ];
        return view('admin.orderStatus', $data);
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $obj = OrderStatus::find($id);
        $this->validate($request, [
            'name' => 'required',
        ], [
            'name.required' => 'The Name field is required.',
        ]);

        $obj->name = $request->name;
        $obj->status = $request->status ?? 'false';
        $obj->save();
        return redirect()->back()->with('success', 'Order status updated successfully');
    }
    ################ order status secion

    private function generateInvoiceID($length = 4)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return 'INV-' . date('Y') . '-' . $randomString;
    }

    ##create invoice
    public function makeInvoice($order_id)
    {
        $orderData = OrderModel::where('order_id', $order_id)->first();
        if ($orderData) {
            $invoice = new InvoiceModel();
            $invoice->invoice_id = $this->generateInvoiceID();
            $invoice->order_id = $orderData->order_id;
            $invoice->amount = $orderData->amount;
            $invoice->status = 'true';
            if ($invoice->save()) {
                return 'true';
            } else {
                return 'false';
            }
        }
    }

    ##export orders
    public function ordersExport()
    {
        return Excel::download(new OrderExport, 'orders.csv');
    }

    ##order list
    public function orders(Request $request)
    {
        // Retrieve search and order status from the request
        $order_data = $request->input('search');
        $order_status = $request->input('orderStatus');

        // Start building the query
        $query = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('order_status', 'orders.order_status', '=', 'order_status.id')
            ->select('users.first_name', 'users.last_name', 'users.email','order_status.name','orders.*')
            ->orderBy('orders.id', 'desc');

        // Add filter for order status if provided
        if (!empty($order_status)) {
            if ($order_status != 'All') {
                $query->where('orders.status', '=', $order_status);
            } else {
                $query->orderBy('orders.id', 'desc');
            }
        }
        // Add filter for order data if provided
        if (!empty($order_data)) {
            $query->where(function ($q) use ($order_data) {
                $q->where('orders.order_id', 'LIKE', "%{$order_data}%")
                    ->orWhere('users.email', 'LIKE', "%{$order_data}%");
            });
        }
        // Paginate the results
        $orders = $query->paginate(10);
        // Return the view with the filtered orders
        return view('admin.orders', ['orders' => $orders]);
    }


    ##order details
    public function ordersDetail($id)
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


        return view('admin.order-detail', $data);
    }

    ##create refund
    public function createRefund($order_id)
    {
        $transaction =  TransactionModel::where('order_id', $order_id)->first();
        $payload = [
            'charge_id' => $transaction['charge_id'],
            'ref_num' => $transaction['ref_num'],
            'amount' => $transaction['amount'],
        ];
        $clover = new Clover();
        $result =  $clover->createRefund($payload);
        if ($result['res'] == 'success') {
            ## make order closed and update order status and make refund in refund table
            $is_updated = OrderModel::where('order_id', $order_id)
                ->update(['status' => 'CLOSED','order_status'=>4]);

            if ($is_updated) {
                $refund = new RefundModel();
                $refund->user_id = $transaction['user_id'];
                $refund->order_id = $order_id;
                $refund->amount = $transaction['amount'];
                $refund->ref_num = $result['data']['metadata']['refNum'];
                $refund->json_data = json_encode($result['data']);
                $refund->save();
                return redirect()->back()->with('success', $result['message']);
            }
        } else {
            return redirect()->back()->with('error', $result['message']);
        }
    }
}

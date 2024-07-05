<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceModel;
use App\Models\OrderItem;
use App\Models\OrderModel;
use App\Models\User;
use App\Models\AddresModel;
use App\Models\SiteInfo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoice = InvoiceModel::orderBy('id', 'desc')->get();

        foreach ($invoice as $inv) {
            $orderdata = OrderModel::where('order_id', $inv['order_id'])->first();
            $users = User::find($orderdata['user_id']);
            $inv['user_name'] = $users['first_name'] . ' ' . $users['last_name'];
            $inv['email'] = $users['email'] ;
        }
        $data = [
            'invoices' =>$invoice,
        ];
        return view('admin.invoice', $data);
    }

    public function viewInvoice($order_id)
    {
        // get orderData
        $orderData = OrderModel::where('order_id', $order_id)->first();
        $orderItems = OrderItem::orderBy('id', 'desc')->where('order_id', $order_id)->get();
        $invoice = InvoiceModel::where('order_id', $order_id)->first();

        $address_count = $orderData->address;
        $address_ =  explode(',', $address_count);
        $addressToSend = [];
        foreach ($address_ as $adr) {
            $adr = AddresModel::where('id', $adr)->first();
            $addressToSend[] = $adr;
        }
        if ($addressToSend[0] == NULL) {
            $addressToSend = [];
        }
        $address = $addressToSend;
        $data = [
            'order' => $orderData,
            'orderItems' => $orderItems,
            'invoice' => $invoice,
            'address' => $address
        ];
        return view('admin.invoice-view', $data);
    }

    public function invoicePdf($order_id)
    {

        $order_data = DB::table('orders')
            ->where('orders.order_id', $order_id)
            ->orWhere('orders.order_id', $order_id)
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('users.first_name', 'users.first_name', 'users.last_name', 'users.last_name', 'users.email', 'users.email', 'orders.*')
            ->first();

        $data['order'] = $order_data;
        $data['orderItems'] = OrderItem::orderBy('id', 'desc')->where('order_id', $order_id)->get();
        // $data['invoiceId'] = InvoiceModel::where('order_id', $order_id)->first()['invoice_id'];
        $invoice =  InvoiceModel::where('order_id', $order_id)->first();
        $data['invoiceId'] =!empty($invoice['invoice_id'])?$invoice['invoice_id']:$invoice['id'];

        $address_count = $order_data->address;
        $address_ =  explode(',', $address_count);
        $addressToSend = [];
        foreach ($address_ as $adr) {
            $adr = AddresModel::where('id', $adr)->first();
            $addressToSend[] = $adr;
        }
        if ($addressToSend[0] == NULL) {
            $addressToSend = [];
        }
        $data['address'] = $addressToSend;


        // return view('invoice',$data);
        // exit;
        $pdf = Pdf::loadView('invoice', $data);
        $filename = $data['invoiceId'] .'.'.'pdf';
        return $pdf->download($filename);
    }
}

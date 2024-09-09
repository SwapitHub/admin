<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\Faq;
use App\Models\ContactUs;

class FaqController extends Controller
{
    public function genstoneFaq()
    {
        $data = Faq::orderBy('order_number', 'asc')->where('status', 'true')->where('faq_category', 5);
        if ($data->exists()) {
            $values = $data->get();
            $output['res'] = 'success';
            $output['msg'] = 'data retrieved successfully';
            $output['data'] = $values;
            return response()->json($output, 200);
        } else {
            $output['res'] = 'error';
            $output['msg'] = 'Faq not found!';
            $output['data'] = [];
            return response()->json($output, 401);
        }
    }

    public function index()
    {
        $output['res'] = 'success';
        $output['msg'] = 'data retrieved successfully';

        $data = Faq::orderBy('id', 'desc')->where('status', 'true')->where('faq_category', 1)->get();
        $output['data'] = $data;
        return response()->json($output, 200);
    }

    public function contactUs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'type' => 'required',
        ]);
        if ($validator->fails()) {
            $output['res'] = 'error';
            $output['msg'] = $validator->errors();
            $output['data'] = [];
            return response()->json($output, 400);
        } else {
            $contact = new ContactUs;
            $contact->type = $request->type;
            $contact->product_data = $request->product_data;
            $contact->first_name = $request->first_name;
            $contact->last_name = $request->last_name;
            $contact->email = $request->email;
            $contact->phone = $request->phone;
            $contact->message = $request->message;
            $contact->send_updates = $request->send_updates ?? 'false';
            $contact->status = 'true';
            $contact->save();
            $output['res'] = 'success';
            $output['msg'] = 'Your message have been submitted';
            $output['data'] = [];
            return response()->json($output, 200);
        }
    }
}

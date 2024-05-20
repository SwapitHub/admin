<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Newsletter;
use Validator;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];
        $messages = [
            'email.required' => 'email id is required.',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) 
        {
            $errors = $validator->errors()->all();
            $output['res'] = 'error';
            $output['msg'] = $errors;
        }
        else
        {
            $newsletter = new Newsletter;
            $exist = Newsletter::where('email',$request->email)->exists();
            if(!$exist)
            {
                $newsletter->email = $request->email;
                $newsletter->save();
                $output['res'] = 'success';
                $output['msg'] = 'Data saved!';
                $output['data'] = Newsletter::latest()->first();
            }else
            {
                $output['res'] = 'success';
                $output['msg'] = 'Email already exist.';
                $output['data'] =[];
            } 
           
        }
       return response()->json($output, 200);
    }
}

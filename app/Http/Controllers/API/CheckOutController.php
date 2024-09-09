<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\OrderItem;
use App\Models\OrderModel;
use App\Models\TransactionModel;
use App\Models\User;
use App\Library\Clover;
use App\Library\UpsShipping;
use Validator;
use App\Mail\OrdersEmail;
use Illuminate\Support\Facades\Mail;

class CheckOutController extends Controller
{

    ## tokenize card
    public function tokenizeCard(Request $request)
    {
        $rules = [
            'card_no' => 'required|numeric|digits:16',
            'exp_date' => 'required',
            'cvv' => 'required|numeric|digits_between:3,4',
            'zip' => 'required|numeric|digits_between:4,8',
        ];
        $messages = [
            'card_no.required' => 'Card number is required.',
            'card_no.numeric' => 'Card number must be numeric.',
            'card_no.digits' => 'Card number must be exactly 16 digits long.',
            'exp_date.required' => 'Expiry date is required.',
            'cvv.required' => 'CVV field is required.',
            'cvv.numeric' => 'CVV must be numeric.',
            'cvv.digits_between' => 'CVV must be between 3 and 4 digits long.',
            'zip.required' => 'Zip code field is required.',
            'zip.numeric' => 'Zip code must be numeric.',
            'zip.digits' => 'Zip code must be between 6 and 8 digits long',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $output['res'] = 'error';
            $output['msg'] = $errors;
            return response()->json($output, 401);
        }
        try {
            $clover = new Clover();
            $cardData = [
                'card_no' => $request->card_no,
                'cvv' => $request->cvv,
                'exp_date' => $request->exp_date,
            ];
            $token = $clover->tokenizeCard($cardData);
            if ($token['res'] == 'success') {
                $output['res'] = $token['res'];
                $output['msg'] = $token['msg'];
                $output['data'] = ['token' => $token['token']];
            } else {
                $output['res'] = $token['res'];
                $output['msg'] =  $token['msg'];
                $output['data'] = $token['token'];
            }
            return response()->json($output, 200);
        } catch (Exception $e) {
            // Handle exceptions
            $output['res'] = 'error';
            $output['msg'] = $e->getMessage(); // Get the error message from the exception
            $output['data'] = [];
            return response()->json($output, 500); // Return a
        }
    }

    private function generateOrderID($length = 10)
    {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return 'WEB-' . date('Y') . '-' . $randomString;
    }

    ## first make payment then create order
    public function checkout(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'order_data' => 'required',
            'card_token' => 'required',
        ];
        $messages = [
            'user_id.required' => 'User id is required.',
            'order_data.required' => 'Order data is required.',
            'card_token.required' => 'Card token is required.',
        ];

        $clientIp = $request->ip();
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $output['res'] = 'error';
            $output['msg'] = $errors;
            return response()->json($output, 401);
        } else {
            $is_valid = json_decode($request->order_data);
            if (empty(get_object_vars($is_valid))) {
                $output['res'] = 'error';
                $output['msg'] = 'Order Data is empty please add some order first.';
                return response()->json($output, 401);
            }
            $order = new OrderModel();
            $order->order_id = $this->generateOrderID();
            $order->user_id = $request->user_id;
            $order->method = "CARD PAYMENT";
            $order->address = $request->address;
            $order->tax = $request->tax;
            $order->shipping = $request->shipping;
            $order->status = 'PROCESSING';
            $order->order_status = 1;
            if ($order->save()) {
                $orderItme = new OrderItem();
                $order_data = json_decode($request->order_data);
                $total_amount = 0;
                $stat = 'true';

                foreach ($order_data as $item) {
                    ## fetch data from cart and add into cart item db
                    $cart_data =  Cart::find($item);
                    $all_amount = $cart_data['ring_price'] + round($cart_data['diamond_price']) + round($cart_data['gemstone_price']);
                    $order_data1 = json_encode($cart_data);
                    $orderIremArr = [
                        'order_id' => $order->order_id,
                        'order_data' => $order_data1,
                        'total_amount' => $all_amount,
                        'status' => 'true',
                    ];
                    //  $saveOrder = OrderItem::create($orderIremArr);
                    $response = $this->saveOrderItem($orderIremArr);
                    if ($response != 'true') {
                        $stat = 'false';
                    }
                    $total_amount += $all_amount;
                    $tatalAmoutWithTaxOrShipping =  round($total_amount) + round($order->tax) + round($order->shipping);
                }
                if ($stat == 'true') {
                    $update_price = OrderModel::find($order->id);
                    $update_price->amount = $total_amount;
                    $update_price->save();
                    ## make transaction after order
                    $order_data = [
                        'transaction_id' => uniqid() . date('YmdHis'),
                        'user_id' => $request->user_id,
                        'order_id' => $order->order_id,
                        // 'amount' => $total_amount,
                        'amount' => $tatalAmoutWithTaxOrShipping,
                        'card_token' => $request->card_token,
                        'paymanet_method' => "CARD PAYMENT",
                        'clientIp' => $clientIp
                    ];
                    $transaction = $this->callPaymentGateway($order_data);
                    if ($transaction['res'] == 'success') {
                        $update_status = OrderModel::find($order->id);
                        $update_status->status = $transaction['order_status'];
                        $update_status->order_status = ($transaction['order_status'] == 'SUCCESS')?1:5;
                        $update_status->save();
                    }
                }
            }
            $returnOrderData = OrderModel::latest()->first();

            ##send email to user for order
            $email_user = User::findOrFail($request->user_id);
            // Mail::to($email_user->email)->send(new OrdersEmail($returnOrderData));
            ##send email to user for order


            $output['res'] = 'success';
            $output['msg'] = 'order successfully created.';
            $output['data'] = $returnOrderData;
            return response()->json($output, 200);
        }
    }



    ## make Transaction
    public function callPaymentGateway($orderData)
    {
        $userEmail = User::find($orderData['user_id'])['email'];
        ## here make charge if it success the reflect status as success or error and also store json response
        $chargeData = [
            'amount' => $orderData['amount'],
            'card_token' => $orderData['card_token'],
            'email' => $userEmail,
            'clientIp' => $orderData['clientIp']
        ];
        $response =  $this->createCharge($chargeData);
        if ($response['res'] == 'success') {
            $orderData['charge_id'] = $response['data']['id'];
            $orderData['ref_num'] = $response['data']['ref_num'];
            $orderData['status'] = 'SUCCESS';
        } else {

            $orderData['status'] = 'FAILED';
        }
        unset($orderData['card_token']);
        unset($orderData['clientIp']);
        $transaction = TransactionModel::create($orderData);
        if ($transaction) {
            ## if order success then make order shippment
            return ['res' => 'success', 'order_status' => $orderData['status']];
        }
    }

    ## Save OrderItem in orderItem table
    private function saveOrderItem($orderIremArr)
    {
        $saveOrder = OrderItem::create($orderIremArr);
        if ($saveOrder) {
            return 'true';
        }
    }

    private function createCharge($charge)
    {
        $clover = new Clover();
        $chargeData = $clover->createCharge($charge);
        return $chargeData;
    }

    ## create shippment after order getting success
    public function createShippment($orderAddress)
    {
        $shipping = new UpsShipping();
        $response = $shipping->createQuote($orderAddress);
        if ($response) {
            $quoteId = $response;
            if ($quoteId) {
                $shipping_created =  $shipping->createShipping($quoteId);
                var_dump($shipping_created);
            }
        }
    }


    public function checkValidPosalCode($postalcode)
    {
        $shipping = new UpsShipping();
        $response = $shipping->isValidPostalCode($postalcode);

        var_dump($response);
    }

    // public function testCharge()
    // {
    //     $data['card_token'] = 'clv_1TSTSEktk6bP6YtMu8TR4Con';
    //     $data['amount'] = '2000';
    //     $data['currency'] = 'usd';
    //     $data['email'] = 'user@gmail.com';
    //     $clover = new Clover();
    //     $charge = $clover->createCharge($data);
    //     if($charge['res'] =='error')
    //     {
    //         echo $charge['message'];
    //     }else
    //     {
    //         var_dump($charge['data']);
    //     }
    // }


}

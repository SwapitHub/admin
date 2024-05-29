<?php

namespace App\Library;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class Clover
{
    public $baseUrl;
    public $merchantId;
    public $apiKey;
    public $authorizationKey;

    public function __construct()
    {
        $this->baseUrl = env('CLOVER_BASE_URI');
        $this->merchantId = env('CLOVER_CLIENT_ID');
        $this->apiKey = env('CLOVER_API_KEY');
        $this->authorizationKey = env('CLOVER_PRIVATE_KEY');
    }
    // generate card token
    // public function tokenizeCard($cardData)
    // {
    //     $expiry =  explode('/', $cardData['exp_date']);
    //     $number =  $cardData['card_no'];
    //     $exp_month = $expiry[0];
    //     $exp_year = $expiry[1];
    //     $cvv = $cardData['cvv'];
    //     $first6 = substr($number, 0, 6);
    //     $last4 = substr($number, -4);
    //     try {
    //         $client = new \GuzzleHttp\Client();
    //         $response = $client->request('POST', 'https://token-sandbox.dev.clover.com/v1/tokens', [
    //             'body' => '{"card":{ "number":"' . $number . '","exp_month":"' . $exp_month . '","exp_year":"' . $exp_year . '","cvv":"' . $cvv . '","last4":"' . $last4 . '","first6":"' . $first6 . '"}}',
    //             'headers' => [
    //                 'accept' => 'application/json',
    //                 'content-type' => 'application/json',
    //                 'apikey' => $this->apiKey,
    //             ],
    //         ]);
    //         $body = json_decode($response->getBody());
    //         return $body->id ?? NULL;
    //     } catch (Throwable $e) {
    //         report($e);
    //         return false;
    //     }
    // }

    public function tokenizeCard($cardData)
    {
        $expiry =  explode('/', $cardData['exp_date']);
        $number =  $cardData['card_no'];
        $exp_month = $expiry[0];
        $exp_year = $expiry[1];
        $cvv = $cardData['cvv'];
        $first6 = substr($number, 0, 6);
        $last4 = substr($number, -4);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://token-sandbox.dev.clover.com/v1/tokens",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'card' => [
                    'number' => $number,
                    'exp_month' => $exp_month,
                    'exp_year' => $exp_year,
                    'cvv' => $cvv,
                    'last4' => $last4 ,
                    'first6' => $first6
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "apikey: $this->apiKey",
                "content-type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return ['res'=>'error','msg'=>'cURL Error #'.$err ,'token'=>[]];
        } else {
            $json_data = json_decode($response,true);
            if(isset($json_data['error']))
            {
                $errmsg = $json_data['error'];
                return ['res'=>'error','msg'=>$errmsg['message'],'token'=>[]];
            }
            else
            {
                return ['res'=>'success','msg'=>'Token retrieved successfully.','token'=>$json_data['id']];
            }
        }
    }

    // public function createCharge($chargeData)
    // {
    //     $output['res'] = 'error';
    //     $output['msg'] = 'error';
    //     $output['data'] = [];
    //     $authorization = 'Bearer' . ' ' . $this->authorizationKey;
    //     $amount = $chargeData['amount'] * 100;
    //     $client = new \GuzzleHttp\Client();
    //     try {
    //         $response = $client->request('POST', 'https://scl-sandbox.dev.clover.com/v1/charges', [
    //             'body' => '{"amount":"'.$amount.'","currency":"usd","capture":true,"ecomind":"ecom","receipt_email":"'.$chargeData['email'].'","source":"'.$chargeData['card_token'].'"}',
    //             'headers' => [
    //                 'accept' => 'application/json',
    //                 'authorization' => $authorization,
    //                 'content-type' => 'application/json',
    //                 'x-forwarded-for' => '3.18.62.57',
    //             ],
    //         ]);
    //         if ($response->getBody()) {
    //             $json_data = json_decode($response->getBody(), true);
    //             return ['res' => 'success', 'message' => 'charge created','data'=>$json_data];
    //         }
    //     } catch (\GuzzleHttp\Exception\ClientException $e) {
    //         //throw $th;
    //         return ['res'=>'error','type' => 'Client error', 'message' => $e->getMessage(), 'status_code' => $e->getResponse()->getStatusCode()];
    //     } catch (\GuzzleHttp\Exception\RequestException $e) {
    //         // Handle other request errors (e.g., network errors)
    //         return ['res'=>'error','type' => 'Request error', 'message' => $e->getMessage()];

    //     } catch (\Exception $e) {
    //         // Handle any other errors
    //         return ['res'=>'error','type' => 'General error', 'message' => $e->getMessage()];
    //     }
    // }

    public function createCharge($chargeData)
    {
        $authorization = 'Bearer' . ' ' . $this->authorizationKey;
        $amount = $chargeData['amount'] * 100;
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://scl-sandbox.dev.clover.com/v1/charges",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'ecomind' => 'ecom',
                'metadata' => [
                    'existingDebtIndicator' => false
                ],
                'source' => $chargeData['card_token'],
                'amount' => $amount,
                'currency' => 'usd',
                'receipt_email' => $chargeData['email']
            ]),
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "authorization: $authorization",
                "content-type: application/json",
                "x-forwarded-for: 3.18.62.57"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // echo "cURL Error #:" . $err;
            return ['res' => 'error', 'type' => 'cURL Error.', 'message' => $err];
        } else {
            $json_data = json_decode($response, true);
            if (isset($json_data['error'])) {
                $erormsg = $json_data['error'];
                return ['res' => 'error', 'code' => $erormsg['code'], 'message' => $erormsg['message']];
            } else {
                return ['res' => 'success', 'message' => 'charge created', 'data' => $json_data];
            }
        }
    }
}

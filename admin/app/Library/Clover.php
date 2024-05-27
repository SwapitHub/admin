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
    public function tokenizeCard($cardData)
    {
        $expiry =  explode('/', $cardData['exp_date']);
        $number =  $cardData['card_no'];
        $exp_month = $expiry[0];
        $exp_year = $expiry[1];
        $cvv = $cardData['cvv'];
        $first6 = substr($number, 0, 6);
        $last4 = substr($number, -4);
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('POST', 'https://token-sandbox.dev.clover.com/v1/tokens', [
                'body' => '{"card":{ "number":"' . $number . '","exp_month":"' . $exp_month . '","exp_year":"' . $exp_year . '","cvv":"' . $cvv . '","last4":"' . $last4 . '","first6":"' . $first6 . '"}}',
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                    'apikey' => $this->apiKey,
                ],
            ]);
            $body = json_decode($response->getBody());
            return $body->id ?? NULL;
        } catch (Throwable $e) {
            report($e);
            return false;
        }
    }

    public function createCharge($chargeData)
    {
        $output['res'] = 'error';
        $output['msg'] = 'error';
        $output['data'] = [];
        $authorization = 'Bearer' . ' ' . $this->authorizationKey;
        $amount = $chargeData['amount'] * 100;
        $client = new \GuzzleHttp\Client();
        try {
            $response = $client->request('POST', 'https://scl-sandbox.dev.clover.com/v1/charges', [
                'body' => '{"amount":"'.$amount.'","currency":"usd","capture":true,"ecomind":"ecom","receipt_email":"'.$chargeData['email'].'","source":"'.$chargeData['card_token'].'"}',
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => $authorization,
                    'content-type' => 'application/json',
                ],
            ]);
            if ($response->getBody()) {
                $json_data = json_decode($response->getBody(), true);
                return ['res' => 'success', 'message' => 'charge created','data'=>$json_data];
            }
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            //throw $th;
            return ['res'=>'error','type' => 'Client error', 'message' => $e->getMessage(), 'status_code' => $e->getResponse()->getStatusCode()];
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle other request errors (e.g., network errors)
            return ['res'=>'error','type' => 'Request error', 'message' => $e->getMessage()];

        } catch (\Exception $e) {
            // Handle any other errors
            return ['res'=>'error','type' => 'General error', 'message' => $e->getMessage()];
        }
    }
}

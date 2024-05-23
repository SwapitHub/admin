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
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://scl-sandbox.dev.clover.com/v1/charges', [
            'body' => '{"amount":2000,"currency":"usd","capture":true,"ecomind":"ecom","receipt_email":"34ey8e3lvq@rfcdrive.com","source":"clv_1TSTS2go8L5Q7Kmzxsraj5kM","partial_redemption":true}',
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Bearer "' . $this->authorizationKey . '"',
                'content-type' => 'application/json',
            ],
        ]);
        echo $response->getBody();
    }
}

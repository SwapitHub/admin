<?php

namespace App\Library;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class Clover
{
    public $baseUrl = 'https://apisandbox.dev.clover.com';
    public $merchantId = 'D5SEVBPF31J31';
    public $appSecrect = '45a9c123-c9ae-e33f-6ec4-474eb894c5ec';

    public function tokenizeCard()
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://token-sandbox.dev.clover.com/v1/tokens', [
            'body' => '{"card":{"brand":"VISA","number":"4242424242424242","exp_month":"12","exp_year":"2030","cvv":"123","last4":"4242","first6":"424242"}}',
            'headers' => [
                'accept' => 'application/json',
                'apikey' => 'e353b72c-8700-ff76-79b8-aaf4ac572f8d',
                'content-type' => 'application/json',
            ],
        ]);
        echo $response->getBody();
    }
}

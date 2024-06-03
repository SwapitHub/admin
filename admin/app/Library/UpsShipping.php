<?php

namespace App\Library;

use Illuminate\Http\Request;
use GuzzleHttp\Client;


class UpsShipping
{
    public $baseUrl;
    public $username;
    public $password;

    public function __construct()
    {
        $this->baseUrl = env('UPS_BASE_URL');
        $this->username = env('UPS_USERNAME');
        $this->password = env('UPS_PASSWORD');
    }

    ## get the barrer token after authorizaion
    public function authorization()
    {
        try {
            $auth_url = $this->baseUrl ."auth";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $auth_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'password=' . $this->password . '&grant_type=password&username=' . $this->username,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            $result =  json_decode($response,true);
            ## return barrer token
            return $result['access_token'];
        } catch (\Throwable $e) {
            var_dump($e);
        }
    }

    public function createQuote($payload)
    {

    }
}

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
    ## generate card token
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
                    'last4' => $last4,
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
            return ['res' => 'error', 'msg' => 'cURL Error #' . $err, 'token' => []];
        } else {
            $json_data = json_decode($response, true);
            if (isset($json_data['error'])) {
                $errmsg = $json_data['error'];
                return ['res' => 'error', 'msg' => $errmsg['message'], 'token' => []];
            } else {
                return ['res' => 'success', 'msg' => 'Token retrieved successfully.', 'token' => $json_data['id']];
            }
        }
    }

    ## create charge
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

    ## refund
    public function createRefund($payload)
    {
        $authorization = 'Bearer' . ' ' . $this->authorizationKey;
        $amount = intval($payload['amount']);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://scl-sandbox.dev.clover.com/v1/refunds",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                'charge' => $payload['charge_id'],
                'amount' => $amount,
                'external_reference_id' => $payload['ref_num'],
                'reason' => 'requested_by_customer'
            ]),
            CURLOPT_HTTPHEADER => [
                "accept: application/json",
                "authorization: $authorization",
                "content-type: application/json"
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
                return ['res' => 'success', 'message' => 'Refund created', 'data' => $json_data];
            }
        }
    }
}

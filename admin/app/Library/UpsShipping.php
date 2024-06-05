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
            $auth_url = $this->baseUrl . "auth";
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
            $result =  json_decode($response, true);
            ## return barrer token
            return $result['access_token'];
        } catch (\Throwable $e) {
            var_dump($e);
        }
    }

    public function createQuote($payload)
    {
        try {
            $quote_url = $this->baseUrl . "quotes";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $quote_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => '{
            "CarrierCode": 1,
            "CodAmount": 1,
            "InsuredValue": "2000",
            "IsCod": true,
            "IsBillToThirdParty": false,
            "BillToThirdPartyPostalCode": "",
            "BillToAccount": "",
            "IsDeliveryConfirmation": false,
            "IsDirectSignature": false,
            "IsDropoff": false,
            "IsPickUpRequested": false,
            "IsRegularPickUp": true,
            "IsReturnShipment": false,
            "IsSaturdayDelivery": false,
            "IsSaturdayPickUp": false,
            "IsSecuredCod": false,
            "IsThermal": false,
            "Length": 0,
            "PackageCode": "21",
            "ReferenceNumber": "475759059",
            "ReturnLabel": false,
            "ServiceCode": "01",
            "ShipDate": "2024-06-10",
            "ShipFrom": {
                "ContactType": 3,
                "CompanyName": "SAMA",
                "FirstName": "FIRSTFIRSTFIRST",
                "LastName": "LASTFIRSTFIRST",
                "StreetAddress": "123 Jill Ave",
                "ApartmentSuite": "Suite 1",
                "City": "TORRANCE",
                "State": "CA",
                "Country": "US",
                "Zip": "90507",
                "TelephoneNo": 212-221-0975",
                "FaxNo": "212-997-5273",
                "Email": "hello@sama@gmail.com",
                "IsResidential": false
            },
            "ShipTo": {
                "ContactType": 11,
                "CompanyName": "TEST COMPANY",
                "FirstName": "FIRSTFIRST",
                "LastName": "LASTFIRST",
                "StreetAddress": "123 Jill Ave",
                "ApartmentSuite": "",
                "City": "CYPRESS",
                "State": "CA",
                "Country": "US",
                "Zip": "90630",
                "TelephoneNo": "7145555871",
                "FaxNo": "",
                "Email": "",
                "IsResidential": false
            },
            "ShipToResidential": false,
            "UPSPickUpType": 0,
            "Weight": 1,
            "Width": 0
        }',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->authorization()
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            // echo $response;
            $response_data = json_decode($response, true);
            return $response_data['QuoteId'];
        } catch (\Throwable $e) {
            //throw $th;
            var_dump($e);
        }
    }

    public function createShipping($quoteId)
    {
        try {
            $base_url = $this->baseUrl;
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $base_url . 'shipments/{quoteID}',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.$this->authorization()
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            // echo $response;
            return json_decode($response);
        } catch (\Throwable $e) {
            var_dump($e);
        }
    }
}

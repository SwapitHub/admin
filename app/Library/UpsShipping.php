<?php

namespace App\Library;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use DateTime;


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
        $this->token = $this->authorization();
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
                CURLOPT_POSTFIELDS => http_build_query([
                    'password' => $this->password,
                    'grant_type' => 'password',
                    'username' => $this->username
                ]),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));

            $response = curl_exec($curl);
            if ($response === false) {
                throw new Exception('Curl error: ' . curl_error($curl));
            }
            curl_close($curl);

            $result = json_decode($response, true);
            if (isset($result['access_token'])) {
                return $result['access_token'];
            } else {
                throw new Exception('Authorization failed: ' . $response);
            }
        } catch (\Throwable $e) {
            // Handle the exception and log the error
            error_log($e->getMessage());
            // Optionally, you can return a specific message or code
            return null;
        }
    }


    public function createQuote($payload)
    {

        // Get the current date and add one day
        $shipDate = new DateTime();
        $shipDate->modify('+1 day');
        $shipTo = [
            "ContactType" => 11,
            "CompanyName" => "TEST COMPANY",
            "FirstName" => $payload["FirstName"],
            "LastName" => $payload["LastName"],
            "StreetAddress" => $payload["StreetAddress"] ?? "123 Jill Ave",
            "ApartmentSuite" => $payload["ApartmentSuite"] ?? "",
            "City" => $payload["City"] ?? "CYPRESS",
            "State" => $payload["State"] ?? "CA",
            "Country" => $payload["Country"] ?? "US",
            "Zip" => $payload["Zip"] ?? "90630",
            "TelephoneNo" => $payload["TelephoneNo"] ?? "7145555871",
            "FaxNo" => "",
            "Email" => $payload["Email"] ?? "",
            "IsResidential" => false
        ];

        $shipFrom = [
            "ContactType" => 3,
            "CompanyName" => "SAMA",
            "FirstName" => "FIRSTFIRSTFIRST",
            "LastName" => "LASTFIRSTFIRST",
            "StreetAddress" => "123 Jill Ave",
            "ApartmentSuite" => "Suite 1",
            "City" => "TORRANCE",
            "State" => "CA",
            "Country" => "US",
            "Zip" => "90507",
            "TelephoneNo" => "212-221-0975",
            "FaxNo" => "212-997-5273",
            "Email" => "test@test.com",
            "IsResidential" => false
        ];

        $payloadData = [
            "CarrierCode" => 1,
            "CodAmount" => 1,
            "InsuredValue" => "2000",
            "IsCod" => true,
            "IsBillToThirdParty" => false,
            "BillToThirdPartyPostalCode" => "",
            "BillToAccount" => "",
            "IsDeliveryConfirmation" => false,
            "IsDirectSignature" => false,
            "IsDropoff" => false,
            "IsPickUpRequested" => false,
            "IsRegularPickUp" => true,
            "IsReturnShipment" => false,
            "IsSaturdayDelivery" => false,
            "IsSaturdayPickUp" => false,
            "IsSecuredCod" => false,
            "IsThermal" => false,
            "Length" => 0,
            "PackageCode" => "21",
            "ReferenceNumber" => "475759059",
            "ReturnLabel" => false,
            "ServiceCode" => "01",
            "ShipDate" => $shipDate->format('Y-m-d'),
            "ShipFrom" => $shipFrom,
            "ShipTo" => $shipTo,
            "ShipToResidential" => false,
            "UPSPickUpType" => 0,
            "Weight" => 1,
            "Width" => 0
        ];
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
            CURLOPT_POSTFIELDS => json_encode($payloadData),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->token
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        // echo $response;
        $response_data = json_decode($response, true);
        return $response_data['QuoteId'];
    }

    public function createShipping($quoteId)
    {
        $output = [
            'res' => 'error',
            'msg' => 'An unexpected error occurred.'
        ];

        $base_url = $this->baseUrl . 'shipments/' . $quoteId;
        $curl = curl_init();
        $dataPayload = '';

        try {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $base_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_POSTFIELDS => $dataPayload,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $this->token,
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($dataPayload)
                ),
            ));

            $response = curl_exec($curl);

            if ($response === false) {
                throw new Exception('cURL Error: ' . curl_error($curl));
            }

            $httpStatusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($httpStatusCode !== 200) {
                throw new Exception('HTTP Error: ' . $httpStatusCode . ' - ' . $response);
            }

            curl_close($curl);

            $shipment = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('JSON Decode Error: ' . json_last_error_msg());
            }

            if (empty($shipment) || !isset($shipment['TrackingNumber'])) {
                throw new Exception('Invalid response received from the API.');
            }

            $output['res'] = 'success';
            $output['msg'] = 'Shipment created';
            $output['data'] = ['TrackingNumber' => $shipment['TrackingNumber']];
            $output['json_data'] = $shipment;
        } catch (Exception $e) {
            $output['msg'] = $e->getMessage();
        } finally {
            if (isset($curl) && is_resource($curl)) {
                curl_close($curl);
            }
        }

        return $output;
    }

    public function isValidPostalCode($postalCode)
    {
        try {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://apibeta.parcelpro.com/v2.0/validate/postal-code/' . $postalCode,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . $this->token
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            // echo $response;
            $result = json_decode($response, true);
            if (isset($result['Code']) && $result['Code'] == 16) {
                $output['res'] = 'error';
                $output['msg'] = $result['Message'];
                $output['data'] = [];

                return $output;
            }

            if(isset($result['Error']))
            {
                $output['res'] = 'error';
                $output['msg'] = 'Something went wrong please try again';
                $output['data'] = [];
                return $output;
            }

            $output['res'] = 'success';
            $output['msg'] = 'shipment available for this postal code.';
            $output['data'] = $result;
            return $output;
        } catch (\Throwable $e) {
            var_dump($e);
        }
    }
}

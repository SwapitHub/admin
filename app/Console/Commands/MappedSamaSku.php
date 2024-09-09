<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\ProductImageModel;
use App\Models\ProductModel;
use GuzzleHttp\Client;

class MappedSamaSku extends Command
{
    protected $signature = 'mapped:sama-sku';
    protected $description = 'Mapping  overmounting sku to sama sku';

    public function __construct()
    {
        parent::__construct();
    }

    ## script to download images on loacl storage
    public function handle()
    {
        // Retrieve product data from the database
        $products = DB::table('products')->select('id', 'sku', 'internal_sku', 'metalType', 'metalColor', 'fractionsemimount')->get();
        $client = new \GuzzleHttp\Client();

        foreach ($products as $product) {
            $sku = $product->sku;

            // Determine the metal code based on metalType and metalColor
            if ($product->metalType == '14KT Gold') {
                if ($product->metalColor == 'White') {
                    $metal = '18W';
                } else if ($product->metalColor == 'Yellow') {
                    $metal = '18Y';
                } else if ($product->metalColor == 'Rose') {
                    $metal = '18R';
                } else if ($product->metalColor == 'Platinum') {
                    $metal = '18P';
                } else {
                    $metal = '18W'; // Default value
                }
            } else {
                $metal = '18W'; // Default value for other metal types
            }

            // Process fractionsemimount to generate a consistent value
            $fractionsemimount = $product->fractionsemimount;
            if (is_null($fractionsemimount) || empty($fractionsemimount) ) {
                $fractionsemimount = '000';
            } else {
                $mount = explode(' ', $fractionsemimount);
                $mount0 = isset($mount[0]) ? $mount[0] : '';
                $mount1 = isset($mount[1]) ? $mount[1] : '';
                $totalMount = explode('/', $mount0);
                $totalMount0 = isset($totalMount[0]) ? $totalMount[0] : '';
                $totalMount1 = isset($totalMount[1]) ? $totalMount[1] : '';
                $fractionsemimount = $totalMount0 . ($totalMount1 ? $totalMount1 : '');
            }

            // Generate the internal SKU
            // $internal_sku = "SA" . str_replace('/', '', $sku) . $metal .'-'.$fractionsemimount;
            $internal_sku = "SA" . str_replace('/', '', $sku) .'-'.$fractionsemimount;

            // Update the product record with the new internal SKU
            $skuupdate = ProductModel::find($product->id);
            $skuupdate->update(['internal_sku' => $internal_sku]);
            $this->info("updated sku from  $product->sku to $internal_sku.");
        }
    }
}

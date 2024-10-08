<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductModel;
use App\Models\ProductPrice;
use App\Models\ProductPrecentageDiscount;
use GuzzleHttp\Client;

class FetchProductPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:product-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch prices for products';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->discount_amount = ProductPrecentageDiscount::first()['amount'] ?? 0;
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Fetching product prices...');

        // Fetch products from the database
        ProductModel::chunk(100, function ($products) {
            foreach ($products as $product) {
                try {
                    $productData = [
                        'sku' => $product['sku'],
                        'metalType' => $product['metalType'],
                        'metalColor' => $product['metalColor'],
                        'finishLevel' => $product['finishLevel'],
                        'fractionsemimount' => $product['fractionsemimount'],
                        'diamondQuality' => $product['diamondQuality'],
                    ];
                    $price = $this->fetchPriceFromAPI($productData);
                    $sku = $product['sku'];
                    $this->info("$sku:  price fetched.");
                } catch (Exception $e) {
                    // Log any errors that occur during the process
                    Log::error('Error fetching price for product ' . $product->sku . ': ' . $e->getMessage());
                    $this->error("Failed to fetch price of this sku :  $product->sku");
                }
            }
        });

        $this->info('Product prices fetched successfully.');
    }

    // Method to fetch price from third-party API
    // private function fetchPriceFromAPI($data)
    // {
    //     $metalColors = ['White', 'Yellow', 'Pink'];
    //     $metalTypes = ['18kt', 'Platinum'];
    //     $diamondQualities = ['SI1, G', 'LAB GROWN VS-SI1, E/F/G'];



    //     if ($data['finishLevel'] == 'Semi-mount (no center)') {
    //         $data['finishLevel'] = 'Semi-mount';
    //     } else if ($data['finishLevel'] == 'Polished Blank (no stones)') {
    //         $data['finishLevel'] = 'Polished Blank (no stones)';
    //     }

    //     foreach ($metalTypes as $metalType) {
    //         foreach ($metalColors as $metalColor) {
    //             foreach ($diamondQualities as $diamondQuality) {
    //                 // Construct the URL with query parameters
    //                 $url = 'http://www.overnightmountings.com/priceapi/service.php?action=pricecalculation&type=json';
    //                 $url .= '&metaltype=' . urlencode($metalType);
    //                 $url .= '&metalcolor=' . urlencode($metalColor);
    //                 $url .= '&stylenumber=' . urlencode($data['sku']);
    //                 $url .= '&quality=' . urlencode($diamondQuality);
    //                 $url .= '&level=' . urlencode($data['finishLevel']);

    //                 // Make HTTP request using GuzzleHttp
    //                 $client = new Client();
    //                 $response = $client->request('GET', $url);

    //                 // Parse JSON response
    //                 $price_data = json_decode($response->getBody(), true);

    //                 // Extract relevant information from the response
    //                 $sku = $data['sku'];
    //                 $price = isset($price_data['price']) ? $price_data['price'] : null;
    //                 $finishLevel = $data['finishLevel'];
    //                 $type = $diamondQuality == 'SI1, G' ? 'natural' : 'lab_grown';

    //                 $stat = 'true';
    //                 // Insert or update data into the database
    //                 $insertorupdate = ProductPrice::updateOrCreate(
    //                     [
    //                         'product_sku' => $sku,
    //                         'metalColor' => $metalColor,
    //                         'metalType' => $metalType,
    //                         'diamond_type' => $type,
    //                         'diamondQuality' => $diamondQuality,
    //                         'finishLevel' => $finishLevel,
    //                     ],
    //                     [
    //                         'product_sku' => $sku,
    //                         'reference_price' => $price,
    //                         'discount_percentage' => $this->discount_amount,
    //                         'price' => $this->calculatePriceDiscount($price),
    //                         'metalColor' => $metalColor,
    //                         'metalType' => $metalType,
    //                         'diamond_type' => $type,
    //                         'diamondQuality' => $diamondQuality,
    //                         'finishLevel' => $finishLevel,
    //                     ]
    //                 );
    //                 try {
    //                     if ($metalColor == 'White' && $metalType == '18kt' && $diamondQuality == 'SI1, G') {
    //                         ProductModel::where('sku', $sku)->update(['white_gold_price' => $this->calculatePriceDiscount($price)]);
    //                         // Log::info("Updated ProductModel for SKU: $sku with white_gold_price: " . $this->calculatePriceDiscount($price));
    //                     } elseif ($metalColor == 'Yellow' && $metalType == '18kt' && $diamondQuality == 'SI1, G') {
    //                         ProductModel::where('sku', $sku)->update(['yellow_gold_price' => $this->calculatePriceDiscount($price)]);
    //                         // Log::info("Updated ProductModel for SKU: $sku with yellow_gold_price: " . $this->calculatePriceDiscount($price));
    //                     } elseif ($metalColor == 'Pink' && $metalType == '18kt' && $diamondQuality == 'SI1, G') {
    //                         ProductModel::where('sku', $sku)->update(['rose_gold_price' => $this->calculatePriceDiscount($price)]);
    //                         // Log::info("Updated ProductModel for SKU: $sku with pink_gold_price: " . $this->calculatePriceDiscount($price));
    //                     } elseif ($metalColor == 'White' && $metalType == 'Platinum' && $diamondQuality == 'SI1, G') {
    //                         ProductModel::where('sku', $sku)->update(['platinum_price' => $this->calculatePriceDiscount($price)]);
    //                         // Log::info("Updated ProductModel for SKU: $sku with platinum_price: " . $this->calculatePriceDiscount($price));
    //                     }
    //                 } catch (Exception $e) {
    //                     Log::error("Failed to update ProductModel for SKU: $sku. Error: " . $e->getMessage());
    //                 }
    //                 if (!$insertorupdate) {
    //                     $stat = 'false';
    //                 }
    //             }
    //         }
    //     }

    //     if ($stat == 'true') {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    private function fetchPriceFromAPI($data)
    {
        $metalColors = ['White', 'Yellow', 'Pink'];
        $metalTypes = ['18kt', 'Platinum'];
        $diamondQualities = ['SI1, G', 'LAB GROWN VS-SI1, E/F/G'];
        $finishLevels = ['Semi-mount', 'Polished Blank (no stones)','Complete'];

        foreach ($finishLevels as $finishLevel) {
            foreach ($metalTypes as $metalType) {
                foreach ($metalColors as $metalColor) {
                    foreach ($diamondQualities as $diamondQuality) {

                        $url = 'http://www.overnightmountings.com/priceapi/service.php?action=pricecalculation&type=json';
                        $url .= '&metaltype=' . urlencode($metalType);
                        $url .= '&metalcolor=' . urlencode($metalColor);
                        $url .= '&stylenumber=' . urlencode($data['sku']);
                        $url .= '&quality=' . urlencode($diamondQuality);
                        $url .= '&level=' . urlencode($finishLevel);

                        $client = new Client();
                        $response = $client->request('GET', $url);

                        $price_data = json_decode($response->getBody(), true);

                        $sku = $data['sku'];
                        $price = isset($price_data['price']) ? $price_data['price'] : null;
                        $type = $diamondQuality == 'SI1, G' ? 'natural' : 'lab_grown';

                        $stat = 'true';
                        $insertorupdate = ProductPrice::updateOrCreate(
                            [
                                'product_sku' => $sku,
                                'metalColor' => $metalColor,
                                'metalType' => $metalType,
                                'diamond_type' => $type,
                                'diamondQuality' => $diamondQuality,
                                'finishLevel' => $finishLevel,
                            ],
                            [
                                'product_sku' => $sku,
                                'reference_price' => $price,
                                'discount_percentage' => $this->discount_amount,
                                'price' => $this->calculatePriceDiscount($price),
                                'metalColor' => $metalColor,
                                'metalType' => $metalType,
                                'diamond_type' => $type,
                                'diamondQuality' => $diamondQuality,
                                'finishLevel' => $finishLevel,
                            ]
                        );

                        try {
                            if ($metalColor == 'White' && $metalType == '18kt' && $diamondQuality == 'SI1, G' && $finishLevel == 'Semi-mount') {
                                ProductModel::where('sku', $sku)->update(['white_gold_price' => $this->calculatePriceDiscount($price)]);
                            } elseif ($metalColor == 'Yellow' && $metalType == '18kt' && $diamondQuality == 'SI1, G' && $finishLevel == 'Semi-mount') {
                                ProductModel::where('sku', $sku)->update(['yellow_gold_price' => $this->calculatePriceDiscount($price)]);
                            } elseif ($metalColor == 'Pink' && $metalType == '18kt' && $diamondQuality == 'SI1, G' && $finishLevel == 'Semi-mount') {
                                ProductModel::where('sku', $sku)->update(['rose_gold_price' => $this->calculatePriceDiscount($price)]);
                            } elseif ($metalColor == 'White' && $metalType == 'Platinum' && $diamondQuality == 'SI1, G' && $finishLevel == 'Semi-mount') {
                                ProductModel::where('sku', $sku)->update(['platinum_price' => $this->calculatePriceDiscount($price)]);
                            }
                        } catch (Exception $e) {
                            Log::error("Failed to update ProductModel for SKU: $sku. Error: " . $e->getMessage());
                        }
                        if (!$insertorupdate) {
                            $stat = 'false';
                        }
                    }
                }
            }
        }

        return $stat == 'true';
    }


    public function calculatePriceDiscount($price)
    {
        if ($price != 0 || $price != null) {
            $discount_amount = $price * ($this->discount_amount / 100);
            $final_price = $price - $discount_amount;
            $final_price = round($final_price); // Format to 2 decimal places
            return $final_price;
        }
    }
}

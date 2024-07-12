<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\ProductImageModel;
use GuzzleHttp\Client;

class DownloadAndUploadImages extends Command
{
    protected $signature = 'images:download-upload';
    protected $description = 'Download images, save to SKU folder, and upload to S3';

    public function __construct()
    {
        parent::__construct();
    }

    ## script to download images on loacl storage
    public function handle()
    {
        $products = DB::table('products')->select('id', 'sku', 'images')->get();
        $client = new \GuzzleHttp\Client();

        foreach ($products as $product) {
            $sku = $product->sku;
            $images = json_decode($product->images);

            if (!is_array($images)) {
                $this->error("Invalid image data for SKU: $sku");
                continue;
            }

            $localFolder = storage_path("app/public/products/$sku");
            if (!file_exists($localFolder)) {
                mkdir($localFolder, 0777, true);
            }

            foreach ($images as $image) {
                // Trim whitespace and sanitize the URL
                $image = trim($image);
                $image = filter_var($image, FILTER_SANITIZE_URL);

                $imageName = basename($image);
                $localPath = "$localFolder/$imageName";
                if (file_exists($localPath)) {
                    $this->info("Image $localPath already exists. Skipping download.");
                    continue;
                }

                try {
                    $response = $client->get($image);
                    file_put_contents($localPath, $response->getBody());
                    // Update the database
                    ProductImageModel::updateOrCreate(
                        ['product_id' => $product->id, 'product_sku' => $sku, 'image_path' => $imageName]
                    );
                    $this->info("Downloaded $image to $localPath.");
                } catch (\Exception $e) {
                    $this->error("Failed to download image: $image. Error: " . $e->getMessage());
                }
            }
        }
    }

    ## script to upload image directoly on s3 bucket
    // public function handle()
    // {
    //     // Retrieve products with SKU and images
    //     $products = DB::table('products')->select('id', 'sku', 'images')->get();
    //     $client = new \GuzzleHttp\Client();

    //     foreach ($products as $product) {
    //         $sku = $product->sku;
    //         $images = json_decode($product->images);

    //         // Check if images data is valid
    //         if (!is_array($images)) {
    //             $this->error("Invalid image data for SKU: $sku");
    //             continue;
    //         }

    //         foreach ($images as $image) {
    //             // Trim whitespace and sanitize the URL
    //             $image = trim($image);
    //             $image = filter_var($image, FILTER_SANITIZE_URL);

    //             $imageName = basename($image);
    //             $s3Path = env('AWS_URL')."public/products/images/$sku/$imageName";

    //             // Check if the image already exists in the S3 bucket
    //             if (Storage::disk('s3')->exists($s3Path)) {
    //                 $this->info("Image $s3Path already exists in S3. Skipping download.");
    //                 continue;
    //             }

    //             try {
    //                 // Download the image
    //                 $response = $client->get($image);
    //                 $imageContents = $response->getBody()->getContents();

    //                 // Upload the image to the S3 bucket
    //                 Storage::disk('s3')->put($s3Path, $imageContents);

    //                 // Update the database
    //                 ProductImageModel::updateOrCreate(
    //                     ['product_id' => $product->id, 'product_sku' => $sku, 'image_path' => $s3Path]
    //                 );

    //                 $this->info("Downloaded $image and uploaded to S3 as $s3Path.");
    //             } catch (\Exception $e) {
    //                 $this->error("Failed to download or upload image: $image. Error: " . $e->getMessage());
    //             }
    //         }
    //     }
    // }
}

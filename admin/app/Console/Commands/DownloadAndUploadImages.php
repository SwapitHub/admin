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

    // public function handle()
    // {
    //     // Retrieve products with SKU and images
    //     $products = DB::table('products')->select('id','sku', 'images')->get();
    //     $client = new \GuzzleHttp\Client();

    //     foreach ($products as $product) {
    //         $sku = $product->sku;
    //         $images = json_decode($product->images);

    //         // Check if images data is valid
    //         if (!is_array($images)) {
    //             $this->error("Invalid image data for SKU: $sku");
    //             continue;
    //         }

    //         // Create local folder for SKU if it doesn't exist
    //         $localFolder = storage_path("app/public/products/$sku");
    //         if (!file_exists($localFolder)) {
    //             mkdir($localFolder, 0777, true);
    //         }

    //         foreach ($images as $image) {
    //             // Trim whitespace and sanitize the URL
    //             $image = trim($image);
    //             $image = filter_var($image, FILTER_SANITIZE_URL);

    //             $imageName = basename($image);
    //             $localPath = "$localFolder/$imageName";

    //             // Check if the image already exists in the folder
    //             if (file_exists($localPath)) {
    //                 $this->info("Image $localPath already exists. Skipping download.");
    //                 continue;
    //             }

    //             try {
    //                 // Download image and save locally
    //                 $response = $client->get($image);
    //                 file_put_contents($localPath, $response->getBody());
    //                 ProductImageModel::createOrUpdate($product->id,['product_id'=>$product->id,'product_sku'=>$product->sku,'image_path'=>$imageName]);

    //                 $this->info("Downloaded $image to $localPath.");
    //             } catch (\Exception $e) {
    //                 $this->error("Failed to download image: $image. Error: " . $e->getMessage());
    //             }
    //         }
    //     }
    // }

    public function handle()
    {
        // Retrieve products with SKU and images
        $products = DB::table('products')->select('id', 'sku', 'images')->get();
        $client = new \GuzzleHttp\Client();

        foreach ($products as $product) {
            $sku = $product->sku;
            $images = json_decode($product->images);

            // Check if images data is valid
            if (!is_array($images)) {
                $this->error("Invalid image data for SKU: $sku");
                continue;
            }

            // Create local folder for SKU if it doesn't exist
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

                // Check if the image already exists in the folder
                if (file_exists($localPath)) {
                    $this->info("Image $localPath already exists. Skipping download.");
                    continue;
                }

                try {
                    // Download image and save locally
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




    // private function uploadFolderToS3($sku, $localFolder)
    // {
    //     $files = scandir($localFolder);

    //     foreach ($files as $file) {
    //         if ($file !== '.' && $file !== '..') {
    //             $localPath = "$localFolder/$file";
    //             $s3Path = "$sku/$file";

    //             try {
    //                 Storage::disk('s3')->put($s3Path, file_get_contents($localPath));
    //                 $this->info("Uploaded $s3Path to S3 successfully.");
    //             } catch (\Exception $e) {
    //                 $this->error("Failed to upload file to S3: $localPath. Error: " . $e->getMessage());
    //             }
    //         }
    //     }
    // }
}

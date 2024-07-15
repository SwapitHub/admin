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
    // public function handle()
    // {
    //     $products = DB::table('products')->select('id', 'sku', 'internal_sku', 'images')->get();
    //     $client = new \GuzzleHttp\Client();

    //     foreach ($products as $product) {
    //         $sku = $product->sku;
    //         $internalSku = $product->internal_sku;
    //         $images = json_decode($product->images);

    //         // Check if images data is valid
    //         if (!is_array($images) || empty($images) || (count($images) == 1 && empty($images[0]))) {
    //             $this->error("Invalid or empty image data for SKU: $internalSku");
    //             continue;
    //         }

    //         $localFolder = storage_path("app/public/products/$internalSku");
    //         if (!file_exists($localFolder)) {
    //             mkdir($localFolder, 0777, true);
    //         }

    //         foreach ($images as $image) {
    //             // Trim whitespace and sanitize the URL
    //             $image = trim($image);
    //             $image = filter_var($image, FILTER_SANITIZE_URL);

    //             $imageName = basename($image);

    //             $splitImage = explode('.', $imageName);
    //             if (!isset($splitImage[2])) {
    //                 $modifiedImageName = $internalSku . '.' . $splitImage[1];
    //             } else {
    //                 $modifiedImageName = $internalSku . '.' . $splitImage[1] . '.' . $splitImage[2];
    //             }


    //             ## Replace the SKU in the image name
    //             $localPath = "$localFolder/$modifiedImageName";

    //             ## Check if the image already exists
    //             if (file_exists($localPath)) {
    //                 $this->info("Image $localPath already exists. Skipping download.");
    //                 continue;
    //             }

    //             try {
    //                 ## Download the image using the original URL
    //                 $response = $client->get($image);
    //                 file_put_contents($localPath, $response->getBody());

    //                 ## Update the database
    //                 ProductImageModel::updateOrCreate(
    //                     ['product_id' => $product->id, 'product_sku' => $internalSku, 'image_path' => $modifiedImageName]
    //                 );

    //                 $this->info("Downloaded $image to $localPath.");
    //             } catch (\Exception $e) {
    //                 $this->error("Failed to download image: $image. Error: " . $e->getMessage());
    //             }
    //         }
    //     }
    // }

    // public function handle()
    // {
    //     $products = DB::table('products')->select('id', 'sku', 'internal_sku', 'images')->get();
    //     $client = new \GuzzleHttp\Client();

    //     foreach ($products as $product) {
    //         $sku = $product->sku;
    //         $internalSku = $product->internal_sku;
    //         $images = json_decode($product->images);

    //         // Check if images data is valid
    //         if (!is_array($images) || empty($images) || (count($images) == 1 && empty($images[0]))) {
    //             $this->error("Invalid or empty image data for SKU: $internalSku");
    //             continue;
    //         }

    //         $localFolder = storage_path("app/public/products/$internalSku");
    //         if (!file_exists($localFolder)) {
    //             mkdir($localFolder, 0777, true);
    //         }

    //         foreach ($images as $image) {
    //             // Trim whitespace and sanitize the URL
    //             $image = trim($image);
    //             $image = filter_var($image, FILTER_SANITIZE_URL);
    //             // Additional check to remove any trailing special characters like ']'
    //             $image = rtrim($image, ']');

    //             // Get the base name of the image
    //             $imageName = basename($image);

    //             // Split the image name by the period to handle different naming patterns
    //             $splitImage = explode('.', $imageName);

    //             // Construct the modified image name based on the internal SKU
    //             if (count($splitImage) >= 3) {
    //                 // Handle names with more than one period
    //                 $extension = array_pop($splitImage);
    //                 $baseName = implode('.', $splitImage);
    //                 $modifiedImageName = $internalSku . '.' . $baseName . '.' . $extension;
    //             } else {
    //                 // Handle names with one period
    //                 $modifiedImageName = $internalSku . '.' . $splitImage[1];
    //             }

    //             // Create the full local path for the image
    //             $localPath = "$localFolder/$modifiedImageName";

    //             // Check if the image already exists
    //             if (file_exists($localPath)) {
    //                 $this->info("Image $localPath already exists. Skipping download.");
    //                 continue;
    //             }

    //             try {
    //                 // Download the image using the original URL
    //                 $response = $client->get($image);
    //                 file_put_contents($localPath, $response->getBody());

    //                 // Update the database
    //                 ProductImageModel::updateOrCreate(
    //                     ['product_id' => $product->id, 'product_sku' => $internalSku, 'image_path' => $modifiedImageName]
    //                 );

    //                 $this->info("Downloaded $image to $localPath.");
    //             } catch (\Exception $e) {
    //                 $this->error("Failed to download image: $image. Error: " . $e->getMessage());
    //                 exit;
    //             }
    //         }
    //     }
    // }



    public function handle()
    {
        $products = DB::table('products')->select('id', 'sku', 'internal_sku', 'images')->get();
        $client = new \GuzzleHttp\Client();

        foreach ($products as $product) {
            $sku = $product->sku;
            // echo $id = $product->id;
            $internalSku = $product->internal_sku;
            $images = json_decode($product->images);

            // Check if images data is valid
            if (!is_array($images) || empty($images) || (count($images) == 1 && empty($images[0]))) {
                $this->error("Invalid or empty image data for SKU: $internalSku");
                continue;
            }

            // echo $internalSku;
          $localFolder = storage_path("app/public/products/$internalSku");

            if (!file_exists($localFolder)) {
                mkdir($localFolder, 0777, true);
            }

            foreach ($images as $image) {
                // Trim whitespace and sanitize the URL
                $imagex = trim($image);
                $image = rtrim($imagex,']');
                $image = filter_var($image, FILTER_SANITIZE_URL);
                if (empty($image)) {
                    $this->info("Empty image URL for SKU: $internalSku. Skipping.");
                    continue;
                }

                // Get the base name of the image
                $imageName = basename($image);

                // Split the image name by the period to handle different naming patterns
                $splitImage = explode('.', $imageName);

                // Construct the modified image name based on the internal SKU
                if (count($splitImage) >= 3) {
                    // Handle names with more than one period
                    $extension = array_pop($splitImage);
                    $baseName = implode('.', $splitImage);
                    $extension = str_replace(']', '', $extension);
                    $modifiedImageName = $internalSku . '.' . $baseName . '.' . $extension;
                } else {
                    // Handle names with one period
                    $extension = str_replace(']', '', $splitImage[1]);
                    $modifiedImageName = $internalSku . '.' . $extension;
                }

                // Create the full local path for the image
                $localPath = "$localFolder/$modifiedImageName";

                // Check if the image already exists
                if (file_exists($localPath)) {
                    $this->info("Image $localPath already exists. Skipping download.");
                    continue;
                }

                try {
                    // Download the image using the original URL
                    $imagex = trim($image);
                    $image = rtrim($imagex);
                    // $image = filter_var($image, FILTER_SANITIZE_URL);
                    $response = $client->get($image);


                    file_put_contents($localPath, $response->getBody());

                    // Update the database
                    ProductImageModel::updateOrCreate(
                        ['product_id' => $product->id, 'product_sku' => $internalSku, 'image_path' => $modifiedImageName]
                    );

                    $this->info("Downloaded $image to $localPath.");
                } catch (\GuzzleHttp\Exception\RequestException $e) {
                    // Handle specific cURL errors
                    if ($e->hasResponse()) {
                        $response = $e->getResponse();
                        $this->error("Failed to download image1: $image. HTTP Status Code: " . $response->getStatusCode() . " - " . $response->getReasonPhrase());
                        exit;
                    } else {
                        $this->error("Failed to download image2: $image. Error: " . $e->getMessage());
                        exit;
                    }
                } catch (\Exception $e) {
                    $this->error("Failed to download image3: $image. Error: " . $e->getMessage());
                    echo $localPath;
                    exit;
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

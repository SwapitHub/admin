<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\ProductVideosModel;
use GuzzleHttp\Client;

class DownloadProductVideos extends Command
{
    protected $signature = 'videos:download';
    protected $description = 'Download Videos from url, save to SKU folder, and upload to S3';

    public function __construct()
    {
        parent::__construct();
    }

    ## script to download images on loacl storage
    public function handle()
{
    $products = DB::table('products')->select('id', 'sku', 'internal_sku', 'videos')->get();
    $client = new \GuzzleHttp\Client();

    foreach ($products as $product) {
        $sku = $product->sku;
        $product_id = $product->id;
        $internalSku = $product->internal_sku;

        if (!is_null($product->videos)) {
            $videos = json_decode($product->videos);

            if (!is_object($videos)) {
                $this->error("Invalid or empty video data for SKU: $internalSku");
                continue;
            }

            $localFolder = storage_path("app/public/videos/$internalSku");
            if (!file_exists($localFolder)) {
                mkdir($localFolder, 0777, true);
            }

            foreach (['white', 'yellow', 'rose'] as $color) {
                if (isset($videos->$color)) {
                    $videoUrl = filter_var($videos->$color, FILTER_SANITIZE_URL);

                    // Validate URL
                    if (filter_var($videoUrl, FILTER_VALIDATE_URL) === FALSE) {
                        $this->error("Invalid URL for {$color} color video: $videoUrl");
                        continue;
                    }

                    $videoName = basename($videoUrl);
                    $extension = pathinfo($videoName, PATHINFO_EXTENSION);
                    $videoFileName = "{$internalSku}.video.{$color}.{$extension}";
                    $localPath = "$localFolder/$videoFileName";

                    try {
                        \Log::info("Attempting to download video from URL: $videoUrl");

                        // Download the video
                        $response = $client->get($videoUrl, ['sink' => $localPath]);

                        // Update the database
                        ProductVideosModel::updateOrCreate(
                            ['product_id' => $product_id, 'product_sku' => $internalSku, 'color' => $color],
                            ['video_path' => $videoFileName]
                        );
                        $this->info("Downloaded {$color} color video $videoUrl to $localPath.");
                    } catch (\GuzzleHttp\Exception\RequestException $e) {
                        $this->error("Failed to download {$color} color video $videoUrl: " . $e->getMessage());
                    } catch (\Exception $e) {
                        $this->error("Unexpected error occurred: " . $e->getMessage());
                    }
                }
            }
        } else {
            $this->error("Empty videos for SKU: $internalSku");
        }
    }
}

    // public function handle()
    // {
    //     $products = DB::table('products')->select('id', 'sku', 'internal_sku', 'videos')->get();
    //     $client = new \GuzzleHttp\Client();

    //     foreach ($products as $product) {
    //         $sku = $product->sku;
    //         $product_id = $product->id;
    //         $internalSku = $product->internal_sku;

    //         if (!is_null($product->videos)) {
    //             $videos = json_decode($product->videos);

    //             // Check if video data is valid
    //             if (!is_object($videos)) {
    //                 $this->error("Invalid or empty video data for SKU: $internalSku");
    //                 continue;
    //             }

    //             $localFolder = storage_path("app/public/videos/$internalSku");
    //             if (!file_exists($localFolder)) {
    //                 mkdir($localFolder, 0777, true);
    //             }

    //             foreach (['white', 'yellow', 'rose'] as $color) {
    //                 if (isset($videos->$color)) {
    //                     $videoUrl = $videos->$color;
    //                     $videoName = basename($videoUrl);
    //                     $extension = pathinfo($videoName, PATHINFO_EXTENSION);
    //                     $videoFileName = "{$internalSku}.video.{$color}.{$extension}";

    //                     // Create the full local path for the video
    //                     $localPath = "$localFolder/$videoFileName";

    //                     try {
    //                         // Download the video
    //                         $response = $client->get($videoUrl, ['sink' => $localPath]);

    //                         // Update the database
    //                         ProductVideosModel::updateOrCreate(
    //                             ['product_id' => $product_id, 'product_sku' => $internalSku, 'color' => $color],
    //                             ['video_path' => $videoFileName]
    //                         );
    //                         $this->info("Downloaded {$color} color video $videoUrl to $localPath.");
    //                     } catch (\Exception $e) {
    //                         $this->error("Failed to download {$color} color video $videoUrl: " . $e->getMessage());
    //                     }
    //                 }
    //             }
    //         } else {
    //             $this->error("Empty videos for SKU: $internalSku");
    //         }
    //     }
    // }

    // public function handle()
    // {
    //     $products = DB::table('products')->select('id', 'sku', 'internal_sku', 'videos')->get();
    //     $client = new \GuzzleHttp\Client();

    //     foreach ($products as $product) {
    //         $sku = $product->sku;
    //         $product_id = $product->id;
    //         $internalSku = $product->internal_sku;

    //         if (!is_null($product->videos)) {
    //             $videos = json_decode($product->videos);

    //             // Check if images data is valid
    //             if (!is_object($videos)) {
    //                 $this->error("Invalid or empty video data for SKU: $internalSku");
    //                 continue;
    //             }

    //             $localFolder = storage_path("app/public/videos/$internalSku");
    //             if (!file_exists($localFolder)) {
    //                 mkdir($localFolder, 0777, true);
    //             }

    //             foreach (['white', 'yellow', 'rose'] as $color) {
    //                 if (isset($videos->$color)) {
    //                     $videoUrl = $videos->$color;
    //                     $videoName = basename($videoUrl);
    //                     $extension = pathinfo($videoName, PATHINFO_EXTENSION);
    //                     $videoFileName = "{$internalSku}.video.{$color}.{$extension}";

    //                     // Create the full local path for the video
    //                     $localPath = "$localFolder/$videoFileName";

    //                     try {
    //                         $response = $client->get($videoUrl);
    //                         file_put_contents($localPath, $response->getBody());
    //                         // Update the database
    //                         ProductVideosModel::updateOrCreate(
    //                             ['product_id' => $product_id, 'product_sku' => $internalSku, 'color'=>$color ,'video_path' => $videoFileName]
    //                         );
    //                         $this->info("Downloaded {$color} color video $videoUrl to $localPath.");
    //                     } catch (\Exception $e) {
    //                         $this->error("Failed to download {$color} color video $videoUrl: " . $e->getMessage());
    //                     }
    //                 }
    //             }
    //         } else {
    //             $this->error("Empty videos for SKU: $internalSku");
    //         }
    //     }
    // }

    // public function handle()
    // {
    //     $products = DB::table('products')->select('id', 'sku', 'internal_sku', 'videos')->get();
    //     $client = new \GuzzleHttp\Client();

    //     foreach ($products as $product) {
    //         $sku = $product->sku;
    //         $product_id = $product->id;
    //         $internalSku = $product->internal_sku;
    //         if (!is_null($product->videos)) {
    //             $videos = json_decode($product->videos);
    //             ## Check if images data is valid
    //             if (!is_object($videos)) {
    //                 $this->error("Invalid or empty video data for SKU: $internalSku");
    //                 continue;
    //             } else {
    //                 ## make logic here to download videos
    //                 $localFolder = storage_path("app/public/videos/$internalSku");
    //                 if (!file_exists($localFolder)) {
    //                     mkdir($localFolder, 0777, true);
    //                 }
    //                 if (isset($videos->white)) {
    //                     $white =  basename($videos->white);
    //                     $extension = pathinfo($white, PATHINFO_EXTENSION);
    //                     $whiteVidName = $internalSku . '.' . 'video' . '.' . 'white' . '.' . $extension;
    //                     ## download this white video in the $localFolder this folder
    //                     // Create the full local path for the image
    //                     $localPath = "$localFolder/$whiteVidName";
    //                     $response = $client->get($videos->white);
    //                     file_put_contents($localPath, $response->getBody());
    //                     $this->info("Downloaded white color video $videos->white to $localPath.");
    //                 }

    //                 if (isset($videos->yellow)) {
    //                     $yellow = basename($videos->yellow);
    //                     $extension = pathinfo($yellow, PATHINFO_EXTENSION);
    //                     $yellowVidName = $internalSku . '.' . 'video' . '.' . 'yellow' . '.' . $extension;
    //                     ## download this white video in the $localFolder this folder
    //                     $localPath = "$localFolder/$yellowVidName";
    //                     $response = $client->get($videos->yellow);
    //                     file_put_contents($localPath, $response->getBody());
    //                     $this->info("Downloaded yellow color video $videos->yellow to $localPath.");
    //                 }

    //                 if (isset($videos->rose)) {
    //                     $reso = basename($videos->rose);
    //                     $extension = pathinfo($reso, PATHINFO_EXTENSION);
    //                     $rosewVidName = $internalSku . '.' . 'video' . '.' . 'rose' . '.' . $extension;
    //                     ## download this white video in the $localFolder this folder
    //                     $localPath = "$localFolder/$rosewVidName";
    //                     $response = $client->get($videos->rose);
    //                     file_put_contents($localPath, $response->getBody());
    //                     $this->info("Downloaded rose color video $videos->rose to $localPath.");
    //                 }
    //             }
    //         } else {
    //             $this->error("Empty videos for SKU: $internalSku");
    //             continue;
    //         }
    //     }
    // }
}

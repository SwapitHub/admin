<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\ProductImageModel;
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
        $products = DB::table('products')->select('id', 'sku', 'internal_sku', 'images', 'videos')->get();
        $client = new \GuzzleHttp\Client();

        foreach ($products as $product) {
            $sku = $product->sku;
            echo $product_id = $product->id;
            $internalSku = $product->internal_sku;
            $videos = json_decode($product->videos);

            // Check if images data is valid
            if (!is_object($videos)) {
                $this->error("Invalid or empty video data for SKU: $internalSku");
                continue;
            }
            // echo $internalSku;
            $localFolder = storage_path("app/public/videos/$internalSku");
            if (!file_exists($localFolder)) {
                mkdir($localFolder, 0777, true);
            }
            var_dump($videos);

            // foreach($videos as $video)
            // {
            //     var_dump($video->rose);

            // }

        }
    }
}

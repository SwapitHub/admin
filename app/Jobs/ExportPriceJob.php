<?php

namespace App\Jobs;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PriceExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExportPriceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {

        $filePath = 'temp/productPrice.csv';
        Excel::store(new PriceExport, $filePath, 'local', \Maatwebsite\Excel\Excel::CSV);
    }
}

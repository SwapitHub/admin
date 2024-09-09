<?php

namespace App\Exports;
use App\Models\ProductPrice;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Schema;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldQueue as LaravelShouldQueue;

class PriceExport implements FromQuery, WithHeadings, WithChunkReading, ShouldAutoSize
{
    public function query()
    {
        return ProductPrice::query()
            // ->select('product_price.id,product_price.product_sku,product_price.metalType,product_price.metalColor,product_price.diamondQuality,product_price.finishLevel,product_price.diamond_type,product_price.reference_price,product_price.discount_percentage,product_price.price', 'menus.name as menu_name')
            ->select([
                'product_price.id',
                'product_price.product_sku',
                'product_price.metalType',
                'product_price.metalColor',
                'product_price.diamondQuality',
                'product_price.finishLevel',
                'product_price.diamond_type',
                'product_price.reference_price',
                'product_price.discount_percentage',
                'product_price.price',
                'menus.name as menu_name'
            ])
            ->join('products', 'products.sku', '=', 'product_price.product_sku', 'inner')
            ->join('menus', 'menus.id', '=', 'products.menu', 'inner')
            ->where('product_price.reference_price', '!=', NULL)
            ->orderBy('product_price.product_sku');
    }

    public function headings(): array
    {
        // $columns = Schema::getColumnListing((new ProductPrice())->getTable());
        $columns = [
            'id',
            'product_sku',
            'metalType',
            'metalColor',
            'diamondQuality',
            'finishLevel',
            'diamond_type',
            'reference_price',
            'discount_percentage',
            'price',
            'category'
        ];
        return $columns;
    }
    public function chunkSize(): int
    {
        return 100;
    }
}

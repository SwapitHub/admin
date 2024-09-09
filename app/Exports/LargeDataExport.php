<?php

namespace App\Exports;

use App\Models\ProductPrice;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;

class LargeDataExport implements FromQuery, WithHeadings, WithChunkReading
{
    use Exportable;

    public function query()
    {
        return ProductPrice::query()
            ->select('product_price.*', 'menus.name as menu_name')
            ->join('products', 'products.sku', '=', 'product_price.product_sku', 'inner')
            ->join('menus', 'menus.id', '=', 'products.menu', 'inner')
            ->orderBy('product_price.product_sku');
    }

    public function headings(): array
    {
       return [
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
            'created_at',
            'updated_at',
            'category'
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}

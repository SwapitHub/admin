<?php

namespace App\Exports;

use App\Models\OrderModel;
use App\Models\OrderItem;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Illuminate\Support\Facades\DB;

class OrderExport implements FromQuery, WithHeadings, WithChunkReading, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    // public function collection()
    // {
    //     // return ProductModel::all();
    //     $data = OrderModel::all();
    //     // Get the column names from the database schema
    //     $columns = Schema::getColumnListing((new OrderModel())->getTable());

    //     // Map the column names to the actual database columns and remove any unwanted columns
    //     $columns = array_map(function ($column) {
    //         // Customize column names if needed
    //         return $column;
    //     }, $columns);

    //     // Add column names as the first row in the collection
    //     $data->prepend($columns);

    //     return $data;
    // }

    public function query()
    {
        return OrderModel::query()
            ->select('product_price.*', 'menus.name as menu_name')
            ->join('products', 'products.sku', '=', 'product_price.product_sku', 'inner')
            ->join('menus', 'menus.id', '=', 'products.menu', 'inner')
            ->orderBy('product_price.product_sku');
    }

    public function headings(): array
    {
        $columns = Schema::getColumnListing((new ProductPrice())->getTable());
        $columns[] = 'category';
        return $columns;
    }

    public function chunkSize(): int
    {
        return 600;
    }
}

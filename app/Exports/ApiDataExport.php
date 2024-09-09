<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ApiDataExport implements FromArray, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {

        // Convert stdClass objects to associative arrays
        return array_map(function ($item) {
            return json_decode(json_encode($item), true);
        }, $this->data);
    }

    public function headings(): array
    {
      // Convert stdClass objects to associative arrays
      $data = array_map(function ($item) {
        return json_decode(json_encode($item), true);
    }, $this->data);

    // Check if data is not empty and get the keys of the first item for headings
    if (!empty($data)) {
        $firstItem = $data[0];
        return array_keys($firstItem);
    }

    // Return default headings if data is empty
    return ['Column1', 'Column2', 'Column3'];
    }
}

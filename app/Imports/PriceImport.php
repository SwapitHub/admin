<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\ProductPrice;
use App\Models\ProductPrecentageDiscount;
use App\Models\ProductModel;

class PriceImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $collection
     */
    protected $discount_amount;
    protected $importedData;
    protected $importStatus;

    public function __construct()
    {
        $this->discount_amount = ProductPrecentageDiscount::first()['amount'] ?? 0;
    }

    public function collection(Collection $collection)
    {
        $productPrice = new ProductPrice;
        $stat = 'true';
        foreach ($collection as $row) {
            if ($row->filter()->isNotEmpty()) {
                $input = $row->toArray();
                if (isset($input['id'])) {
                    unset($input['id']);
                }
                if (isset($input['created_at'])) {
                    unset($input['created_at']);
                }
                if (isset($input['updated_at'])) {
                    unset($input['updated_at']);
                }
                $matchData = [
                    "product_sku" => $input['product_sku'],
                    "metalType" => $input['metaltype'],
                    "metalColor" => $input['metalcolor'],
                    "diamondQuality" => $input['diamondquality'],
                    "finishLevel" => $input['finishlevel'],
                ];
                $is_saved = ProductPrice::updateOrCreate($matchData, $input);
                if (!$is_saved) {
                    $stat = 'false';
                }
            }
        }
        if ($stat == 'true') {
            $this->getImportedData(['is_updated' => 'true']);
        } else {
            $this->getImportedData(['is_updated' => 'false']);
        }
        $this->importStatus = ['is_updated' => $stat ? 'true' : 'false'];
        $this->importedData = $collection;
    }
    public function getImportedData()
    {
        return $this->importedData;
    }

    public function getImportStatus()
    {
        return $this->importStatus;
    }
}

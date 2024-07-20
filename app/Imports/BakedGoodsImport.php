<?php

namespace App\Imports;

use App\Models\BakedGood;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BakedGoodsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            BakedGood::updateOrCreate([
                'name' => $row['name'],
                'price' => $row['price'],
                'is_available' => $row['is_available'],
                'description' => $row['description'],
                'weight_gram' => $row['weight_gram'],
            ]);
        }
    }
}

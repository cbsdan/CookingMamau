<?php

namespace App\Imports;

use App\Models\Ingredient;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IngredientsImport implements ToCollection, WithHeadingRow
{
    /**
     * @param Collection $rows
    *
    */

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Ingredient::updateOrCreate([
                'name' => $row['name'],
                'unit' => $row['unit'],
                'image_path' => $row['image_path'],
            ]);
        }
    }
}

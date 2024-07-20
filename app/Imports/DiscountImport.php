<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Discount;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DiscountImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $rows
    *
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Discount::updateOrCreate([
                'discount_code' => $row['discount_code'],
                'percent' => $row['percent'],
                'max_number_buyer' => $row['max_number_buyer'],
                'min_order_price' => $row['min_order_price'],
                'is_one_time_use' => $row['is_one_time_use'],
                'discount_start' => $this->transformDate($row['discount_start']),
                'discount_end' => $this->transformDate($row['discount_end']),
                'image_path' => $row['image_path'],
                'max_discount_amount' => $row['max_discount_amount'],
            ]);
        }
    }

    /**
     * Convert Excel date to PHP date format
     *
     * @param mixed $value
     * @return string
     */
    private function transformDate($value)
    {
        if (is_numeric($value)) {
            // Excel serial date format, convert to date
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format('Y-m-d');
        }
        return $value; // Otherwise return the value as it is
    }
}

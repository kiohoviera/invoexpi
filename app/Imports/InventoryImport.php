<?php

namespace App\Imports;

use App\Models\Inventories;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class InventoryImport implements ToModel, WithStartRow
{

    public function startRow(): int
    {
        return 2;
    }

    /**
     * @param array $row
     *
     * @return User|null
     */
    public function model(array $row)
    {
        Inventories::create([
            'serial_number' => $row[0],
            'sku' =>  $row[1],
            'product_name' =>  $row[2],
            'quantity' =>  $row[3],
            'location' =>  $row[4],
            'expiration_date' =>  $row[5]
        ]);
    }
}

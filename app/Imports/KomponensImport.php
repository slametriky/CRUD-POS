<?php

namespace App\Imports;

use App\Models\Komponen;
use Maatwebsite\Excel\Concerns\ToModel;

class KomponensImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // dd($row);
        return new Komponen([
            'kode_komponen' => substr($row[0], -3),
            'desk_komponen' => $row[1],
            'ro_id'         => $row[2],
        ]); 
    }
}

<?php

namespace App\Imports;

use App\Models\JenisPendanaan;
use Maatwebsite\Excel\Concerns\ToModel;

class AkunsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new JenisPendanaan([
            'kode_pendanaan'     => $row[0],
            'desk_pendanaan'     => $row[1],
            'cara_tarik' => $row[2],
        ]);
    }
}

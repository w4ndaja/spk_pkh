<?php

namespace App\Imports;

use App\Models\Kriteria;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KriteriaImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            Kriteria::updateOrCreate([
                'kode_kri' => $row['kode_kriteria'],
            ],[
                'nama_kri' => $row['nama_kriteria'],
            ]);
        }
    }
}

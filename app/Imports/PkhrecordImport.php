<?php

namespace App\Imports;

use App\Models\Masyarakat;
use App\Models\PkhRecord;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PkhrecordImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $masyarakat = Masyarakat::firstOrCreate([
                'nik' => $row['nik_ktp'],
            ], [
                'nama' => $row['nama_penerima'],
                'alamat' => '-',
            ]);
            PkhRecord::create([
                'masyarakat_id' => $masyarakat->id_ms ?? $masyarakat->id,
                'tahap' => $row['tahap'],
                'bansos' => $row['bansos'],
                'idsemesta' => $row['idsemesta'],
                'no_kel' => $row['no_kel'],
                'nm_prov' => $row['nm_prov'],
                'nm_kab' => $row['nm_kab'],
                'nm_kec' => $row['nm_kec'],
                'nm_kel' => $row['nm_kel_desa'],
                'alamat' => $row['alamat'],
                'umur' => $row['umur'],
                'tanggungan' => $row['tanggungan'],
                'status' => $row['status'],
                'penghasilan' => $row['penghasilan'],
            ]);
        }
    }
}

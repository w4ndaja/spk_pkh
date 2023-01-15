<?php

namespace App\Imports;

use App\Models\Masyarakat;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class MasyarakatImport implements ToCollection, WithHeadingRow, WithCalculatedFormulas
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            Masyarakat::updateOrCreate([
                'nik' => $row['nik_ktp'],
            ], [
                'nama' => $row['nama_penerima'],
                'alamat' => $row['alamat'],
                'hamil' => $row['hamil'],
                'pendidikan' => $row['pendidikan'],
                'tanggungan' => $row['tanggungan'],
                'penghasilan' => $row['penghasilan'],
                'dissabilitas' => $row['dissabilitas'],
                'umur' => $row['umur'],
            ]);
        }
    }
}

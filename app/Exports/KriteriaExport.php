<?php

namespace App\Exports;

use App\Models\Kriteria;
use App\Models\PkhRecord;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class KriteriaExport implements FromView
{
    public function view(): View
    {
        return view('kriteria.export', [
            'kriteria' => Kriteria::all()
        ]);
    }
}

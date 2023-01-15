<?php

namespace App\Exports;

use App\Models\PkhRecord;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PkhRecordExport implements FromView
{
    public function view(): View
    {
        return view('pkh-record.export', [
            'pkhRecord' => PkhRecord::all()
        ]);
    }
}

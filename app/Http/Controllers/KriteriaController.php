<?php

namespace App\Http\Controllers;

use App\Exports\KriteriaExport;
use App\Exports\PkhRecordExport;
use App\Imports\KriteriaImport;
use Illuminate\Http\Request;

use App\Models\Kriteria;
use App\Models\PembobotanLmuSub;
use App\Models\PembobotanSub;
use App\Models\SubKriteria;
use App\Models\ValuePembobotanSub;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use PDO;

class KriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $judul = "Data Kriteria";
        $data = Kriteria::all();
        return view('kriteria.index', ['data' => $data, 'judul' => $judul]);
    }

    public function download()
    {
        $judul = "Data Kriteria";
        $data = Kriteria::all();
        return view('kriteria.download', ['data' => $data, 'judul' => $judul]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Kriteria::create($request->all());

        return redirect('kriteria')->with('input', 'data berhasil ditambah');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kriteria = DB::table('tb_kriteria')->where('id_kri', $id)->first();
        return view('kriteria.edit', ['data' => $kriteria]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Kriteria::where('id_kri', $id)
            ->update([
                'kode_kri' => $request->kode_kri,
                'nama_kri' => $request->nama_kri
            ]);
        return redirect('kriteria')->with('pesan', "Data Berhasil Diubah");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('tb_kriteria')->where('id_kri', '=', $id)->delete();

        return redirect('kriteria')->with('pesan', "Data Berhasil Dihapus");
    }

    public function export(Request $request)
    {
        return Excel::download(new KriteriaExport, 'kriteria.xlsx');
    }

    public function import(Request $request)
    {
        Excel::import(new KriteriaImport, $request->file('excel'), null, \Maatwebsite\Excel\Excel::XLSX);
        return back()->with('input', 'Data berhasil di impor!');
    }

    public function pembobotan(Request $request, $kriteria)
    {
        $parentKriteria = Kriteria::find($kriteria);
        $kriteria = $parentKriteria->subKriteria;
        $lastPembobotanCode = PembobotanSub::where('kriteria_id', $parentKriteria->id_kri)->latest()->firstOrNew()->kode;
        $valuePembobotans = ValuePembobotanSub::whereHas('pembobotan', function ($item) use ($lastPembobotanCode) {
            $item->whereKode($lastPembobotanCode);
        })->get();
        $pembobotan = PembobotanSub::whereKode($lastPembobotanCode)->get();
        $computedPembobotan = $kriteria->map(function ($item) use ($kriteria, $valuePembobotans) {
            return $kriteria->map(function ($kri) use ($valuePembobotans, $item) {
                return optional($valuePembobotans->where('x_kriteria_id', $item->id_sub)->where('y_kriteria_id', $kri->id_sub)->first())->value;
            })->toArray();
        });
        $normalisasi = $kriteria->map(function ($item, $itemKey) use ($kriteria, $valuePembobotans, $pembobotan) {
            return $kriteria->map(function ($_item) use ($valuePembobotans, $pembobotan, $item, $itemKey) {
                $value = optional($valuePembobotans->where('x_kriteria_id', $item->id_sub)->where('y_kriteria_id', $_item->id_sub)->first())->value;
                $jumlah = array_key_exists($itemKey, $pembobotan->toArray()) ? $pembobotan->toArray()[$itemKey]['jumlah'] : 1;
                return $value / $jumlah;
            });
        });
        $sumNormalisasi = $normalisasi->map(function ($item) {
            return round(array_reduce($item->toArray(), function ($carry, $item) {
                return $carry + $item;
            }));
        });
        $kriteriaLength = $kriteria->count();
        $prioritas = $kriteria->map(function ($item, $i) use ($normalisasi, $kriteriaLength) {
            $summaries = $normalisasi->reduce(function ($carry, $normValue) use ($i) {
                return $carry + floatval($normValue[$i]);
            });
            return $summaries / $kriteriaLength;
        });
        $sumPrioritas = round($prioritas->sum());
        $pembobotan2 = $computedPembobotan->map(function ($valX, $iX) use ($prioritas) {
            return collect($valX)->map(function ($valY, $iY) use ($prioritas, $iX) {
                return $valY * $prioritas[$iX];
            });
        });
        $sumPembobotan2 = $kriteria->map(function ($valKri, $iKri) use ($pembobotan2) {
            return $pembobotan2->reduce(function ($carry, $value) use ($iKri) {
                return $carry + $value[$iKri];
            }, 0);
        });
        $hasilPembobotan = $kriteria->map(function ($valKri, $iKri) use ($sumPembobotan2, $prioritas) {
            return $sumPembobotan2[$iKri] / ($prioritas[$iKri] == 0 ? 1 : $prioritas[$iKri]);
        });
        $sumHasilPembobotan = $hasilPembobotan->reduce(function ($carry, $value) {
            return $carry + $value;
        }, 0) / ($hasilPembobotan->count() > 0 ? $hasilPembobotan->count() : 1);
        $ci = ($sumHasilPembobotan - $kriteriaLength) / ($kriteriaLength - 1);
        $cr = $ci / 1.24;

        $lastPembobotanLmuCode = PembobotanLmuSub::where('kriteria_id', $parentKriteria->id_kri)->latest()->firstOrNew()->code;
        $lastPembobotanLmu = PembobotanLmuSub::whereCode($lastPembobotanLmuCode)->get();
        $tablePembobotanLmu = $lastPembobotanLmu->groupBy('y_kriteria_id')->map(function ($item) {
            return $item->mapWithKeys(function ($_item) {
                return [$_item->x_kriteria_id => $_item->toArray()];
            })->toArray();
        })->toArray();

        $rataRataGeoFuzzy = $kriteria->map(function ($item) use ($tablePembobotanLmu, $kriteriaLength) {
            return [
                'l' => pow(collect(array_key_exists($item->id_sub, $tablePembobotanLmu) ? $tablePembobotanLmu[$item->id_sub] : [])->reduce(function ($carry, $_item) {
                    return $carry * $_item['l'];
                }, 1), (1 / $kriteriaLength)),
                'm' => pow(collect(array_key_exists($item->id_sub, $tablePembobotanLmu) ? $tablePembobotanLmu[$item->id_sub] : [])->reduce(function ($carry, $_item) {
                    return $carry * $_item['m'];
                }, 1), (1 / $kriteriaLength)),
                'u' => pow(collect(array_key_exists($item->id_sub, $tablePembobotanLmu) ? $tablePembobotanLmu[$item->id_sub] : [])->reduce(function ($carry, $_item) {
                    return $carry * $_item['u'];
                }, 1), (1 / $kriteriaLength)),
            ];
        });

        $sumPemangkatanIh = [
            'l' => $rataRataGeoFuzzy->reduce(function ($carry, $item) {
                return $carry + $item['l'];
            }, 0),
            'm' => $rataRataGeoFuzzy->reduce(function ($carry, $item) {
                return $carry + $item['m'];
            }, 0),
            'u' => $rataRataGeoFuzzy->reduce(function ($carry, $item) {
                return $carry + $item['u'];
            }, 0),
        ];

        $inversPemangkatanIh = [
            'l' => 1 / ($sumPemangkatanIh['l'] > 0 ? $sumPemangkatanIh['l'] : 1),
            'm' => 1 / ($sumPemangkatanIh['m'] > 0 ? $sumPemangkatanIh['m'] : 1),
            'u' => 1 / ($sumPemangkatanIh['u'] > 0 ? $sumPemangkatanIh['u'] : 1),
        ];

        $transform = $rataRataGeoFuzzy->map(function ($item) use ($inversPemangkatanIh) {
            return [
                'l' => $item['l'] * $inversPemangkatanIh['l'],
                'm' => $item['m'] * $inversPemangkatanIh['m'],
                'u' => $item['u'] * $inversPemangkatanIh['u'],
            ];
        });
        $dataPembobotan = (new PembobotanController)->dataPembobotan();
        $wKriteria = $dataPembobotan['transform']->where('id_kri', $parentKriteria->id_kri)->first();
        $wSubKriteria = $transform->map(function ($item) use ($wKriteria) {
            return [
                'l' => $item['l'] * $wKriteria['l'],
                'm' => $item['m'] * $wKriteria['m'],
                'u' => $item['u'] * $wKriteria['u'],
            ];
        });
        $defuzzifikasi = $kriteria->map(function ($item, $i) use ($wSubKriteria) {
            return ((($wSubKriteria[$i]['u'] - $wSubKriteria[$i]['l']) + ($wSubKriteria[$i]['m'] - $wSubKriteria[$i]['l'])) / 3) + $wSubKriteria[$i]['l'];
        });

        return view('pembobotan-sub.index', [
            'judul' => "Pembobotan Sub {$parentKriteria->nama_kri}",
            'parentKriteria' => $parentKriteria,
            'kriteria' => $kriteria,
            'valuePembobotans' => $valuePembobotans,
            'computedPembobotan' => $computedPembobotan,
            'pembobotan' => $pembobotan,
            'normalisasi' => $normalisasi,
            'prioritas' => $prioritas,
            'sumNormalisasi' => $sumNormalisasi,
            'sumPrioritas' => $sumPrioritas,
            'pembobotan2' => $pembobotan2,
            'sumPembobotan2' => $sumPembobotan2,
            'hasilPembobotan' => $hasilPembobotan,
            'sumHasilPembobotan' => $sumHasilPembobotan,
            'ci' => $ci,
            'cr' => $cr,
            'tablePembobotanLmu' => $tablePembobotanLmu,
            'rataRataGeoFuzzy' => $rataRataGeoFuzzy,
            'sumPemangkatanIh' => $sumPemangkatanIh,
            'inversPemangkatanIh' => $inversPemangkatanIh,
            'transform' => $transform,
            'defuzzifikasi' => $defuzzifikasi,
            'wKriteria' => $wKriteria,
            'wSubKriteria' => $wSubKriteria,
        ]);
    }

    public function storePembobotan(Request $request, $kriteria)
    {
        $form = $request->all();
        $kriteria = SubKriteria::where('id_kri', $kriteria)->get();
        $lastPembobotanCode = PembobotanSub::latest()->firstOrNew()->kode;
        $nextPembobotanCode = ($lastPembobotanCode > 0 ? $lastPembobotanCode : 0) + 1;
        foreach ($kriteria as $key => $item) {
            $pembobotan = $item->pembobotans()->create([
                'kriteria_id' => request('kriteria'),
                'kode' => $nextPembobotanCode,
                'jumlah' => $form['total'][$key]
            ]);
            foreach ($kriteria as $y => $yItem) {
                $pembobotan->pembobotanSubValues()->updateOrCreate([
                    'x_kriteria_id' => $item->id_sub,
                    'y_kriteria_id' => $yItem->id_sub,
                ], [
                    'value' => $form['value'][$key][$y]
                ]);
            }
        }
        return back();
    }

    public function storePembobotanLmu(Request $request, $kriteria)
    {
        $form = collect($request->all()['pembobotan']);
        $pembobotanLmu = [];
        $lastCode = intval(PembobotanLmuSub::latest()->firstOrNew()->code);
        $nextCode = ($lastCode > 0 ? $lastCode : 0) + 1;
        foreach ($form as $y_kriteria_id => $items) {
            foreach ($items as $x_kriteria_id => $item) {
                $pembobotanLmu[] = [
                    'y_kriteria_id' => $y_kriteria_id,
                    'x_kriteria_id' => $x_kriteria_id,
                    'l' => $item['l'],
                    'm' => $item['m'],
                    'u' => $item['u'],
                    'code' => $nextCode,
                    'kriteria_id' => $kriteria
                ];
            }
        }
        PembobotanLmuSub::insert($pembobotanLmu);
        return back();
    }


    public static function pembobotanByKriteria($kriteria)
    {
        $parentKriteria = Kriteria::find($kriteria);
        $kriteria = $parentKriteria->subKriteria;
        $lastPembobotanCode = PembobotanSub::where('kriteria_id', $parentKriteria->id_kri)->latest()->firstOrNew()->kode;
        $valuePembobotans = ValuePembobotanSub::whereHas('pembobotan', function ($item) use ($lastPembobotanCode) {
            $item->whereKode($lastPembobotanCode);
        })->get();
        $pembobotan = PembobotanSub::whereKode($lastPembobotanCode)->get();
        $computedPembobotan = $kriteria->map(function ($item) use ($kriteria, $valuePembobotans) {
            return $kriteria->map(function ($kri) use ($valuePembobotans, $item) {
                return optional($valuePembobotans->where('x_kriteria_id', $item->id_sub)->where('y_kriteria_id', $kri->id_sub)->first())->value;
            })->toArray();
        });
        $normalisasi = $kriteria->map(function ($item, $itemKey) use ($kriteria, $valuePembobotans, $pembobotan) {
            return $kriteria->map(function ($_item) use ($valuePembobotans, $pembobotan, $item, $itemKey) {
                $value = optional($valuePembobotans->where('x_kriteria_id', $item->id_sub)->where('y_kriteria_id', $_item->id_sub)->first())->value;
                $jumlah = array_key_exists($itemKey, $pembobotan->toArray()) ? $pembobotan->toArray()[$itemKey]['jumlah'] : 1;
                return $value / $jumlah;
            });
        });
        $sumNormalisasi = $normalisasi->map(function ($item) {
            return round(array_reduce($item->toArray(), function ($carry, $item) {
                return $carry + $item;
            }));
        });
        $kriteriaLength = $kriteria->count();
        $prioritas = $kriteria->map(function ($item, $i) use ($normalisasi, $kriteriaLength) {
            $summaries = $normalisasi->reduce(function ($carry, $normValue) use ($i) {
                return $carry + floatval($normValue[$i]);
            });
            return $summaries / $kriteriaLength;
        });
        $sumPrioritas = round($prioritas->sum());
        $pembobotan2 = $computedPembobotan->map(function ($valX, $iX) use ($prioritas) {
            return collect($valX)->map(function ($valY, $iY) use ($prioritas, $iX) {
                return $valY * $prioritas[$iX];
            });
        });
        $sumPembobotan2 = $kriteria->map(function ($valKri, $iKri) use ($pembobotan2) {
            return $pembobotan2->reduce(function ($carry, $value) use ($iKri) {
                return $carry + $value[$iKri];
            }, 0);
        });
        $hasilPembobotan = $kriteria->map(function ($valKri, $iKri) use ($sumPembobotan2, $prioritas) {
            return $sumPembobotan2[$iKri] / ($prioritas[$iKri] == 0 ? 1 : $prioritas[$iKri]);
        });
        $sumHasilPembobotan = $hasilPembobotan->reduce(function ($carry, $value) {
            return $carry + $value;
        }, 0) / ($hasilPembobotan->count() > 0 ? $hasilPembobotan->count() : 1);
        $ci = ($sumHasilPembobotan - $kriteriaLength) / ($kriteriaLength - 1);
        $cr = $ci / 1.24;
        $lastPembobotanLmuCode = PembobotanLmuSub::where('kriteria_id', $parentKriteria->id_kri)->latest()->firstOrNew()->code;
        $lastPembobotanLmu = PembobotanLmuSub::whereCode($lastPembobotanLmuCode)->get();
        $tablePembobotanLmu = $lastPembobotanLmu->groupBy('y_kriteria_id')->map(function ($item) {
            return $item->mapWithKeys(function ($_item) {
                return [$_item->x_kriteria_id => $_item->toArray()];
            })->toArray();
        })->toArray();

        $rataRataGeoFuzzy = $kriteria->map(function ($item) use ($tablePembobotanLmu, $kriteriaLength) {
            return [
                'l' => pow(collect(array_key_exists($item->id_sub, $tablePembobotanLmu) ? $tablePembobotanLmu[$item->id_sub] : [])->reduce(function ($carry, $_item) {
                    return $carry * $_item['l'];
                }, 1), (1 / $kriteriaLength)),
                'm' => pow(collect(array_key_exists($item->id_sub, $tablePembobotanLmu) ? $tablePembobotanLmu[$item->id_sub] : [])->reduce(function ($carry, $_item) {
                    return $carry * $_item['m'];
                }, 1), (1 / $kriteriaLength)),
                'u' => pow(collect(array_key_exists($item->id_sub, $tablePembobotanLmu) ? $tablePembobotanLmu[$item->id_sub] : [])->reduce(function ($carry, $_item) {
                    return $carry * $_item['u'];
                }, 1), (1 / $kriteriaLength)),
            ];
        });

        $sumPemangkatanIh = [
            'l' => $rataRataGeoFuzzy->reduce(function ($carry, $item) {
                return $carry + $item['l'];
            }, 0),
            'm' => $rataRataGeoFuzzy->reduce(function ($carry, $item) {
                return $carry + $item['m'];
            }, 0),
            'u' => $rataRataGeoFuzzy->reduce(function ($carry, $item) {
                return $carry + $item['u'];
            }, 0),
        ];

        $inversPemangkatanIh = [
            'l' => 1 / ($sumPemangkatanIh['l'] > 0 ? $sumPemangkatanIh['l'] : 1),
            'm' => 1 / ($sumPemangkatanIh['m'] > 0 ? $sumPemangkatanIh['m'] : 1),
            'u' => 1 / ($sumPemangkatanIh['u'] > 0 ? $sumPemangkatanIh['u'] : 1),
        ];

        $transform = $rataRataGeoFuzzy->map(function ($item) use ($inversPemangkatanIh) {
            return [
                'l' => $item['l'] * $inversPemangkatanIh['l'],
                'm' => $item['m'] * $inversPemangkatanIh['m'],
                'u' => $item['u'] * $inversPemangkatanIh['u'],
            ];
        });
        $dataPembobotan = (new PembobotanController)->dataPembobotan();
        $wKriteria = $dataPembobotan['transform']->where('id_kri', $parentKriteria->id_kri)->first();
        $wSubKriteria = $transform->map(function ($item) use ($wKriteria) {
            return [
                'l' => $item['l'] * $wKriteria['l'],
                'm' => $item['m'] * $wKriteria['m'],
                'u' => $item['u'] * $wKriteria['u'],
            ];
        });
        $defuzzifikasi = $kriteria->map(function ($item, $i) use ($wSubKriteria) {
            return ((($wSubKriteria[$i]['u'] - $wSubKriteria[$i]['l']) + ($wSubKriteria[$i]['m'] - $wSubKriteria[$i]['l'])) / 3) + $wSubKriteria[$i]['l'];
        });

        return view('pembobotan-sub.index', [
            'judul' => "Pembobotan Sub {$parentKriteria->nama_kri}",
            'parentKriteria' => $parentKriteria,
            'kriteria' => $kriteria,
            'valuePembobotans' => $valuePembobotans,
            'computedPembobotan' => $computedPembobotan,
            'pembobotan' => $pembobotan,
            'normalisasi' => $normalisasi,
            'prioritas' => $prioritas,
            'sumNormalisasi' => $sumNormalisasi,
            'sumPrioritas' => $sumPrioritas,
            'pembobotan2' => $pembobotan2,
            'sumPembobotan2' => $sumPembobotan2,
            'hasilPembobotan' => $hasilPembobotan,
            'sumHasilPembobotan' => $sumHasilPembobotan,
            'ci' => $ci,
            'cr' => $cr,
            'tablePembobotanLmu' => $tablePembobotanLmu,
            'rataRataGeoFuzzy' => $rataRataGeoFuzzy,
            'sumPemangkatanIh' => $sumPemangkatanIh,
            'inversPemangkatanIh' => $inversPemangkatanIh,
            'transform' => $transform,
            'defuzzifikasi' => $defuzzifikasi,
            'wKriteria' => $wKriteria,
            'wSubKriteria' => $wSubKriteria,
        ]);
    }
}

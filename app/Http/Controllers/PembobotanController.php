<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Pembobotan;
use App\Models\PembobotanLmu;
use App\Models\ValuePembobotan;
use Illuminate\Http\Request;
use Spatie\LaravelIgnition\ContextProviders\LaravelRequestContextProvider;

class PembobotanController extends Controller
{
    public function index()
    {
        $dataPembobotan = $this->dataPembobotan();

        return view('pembobotan.index', [
            'judul' => "Pembobotan",
            'kriteria' => $dataPembobotan['kriteria'],
            'valuePembobotans' => $dataPembobotan['valuePembobotans'],
            'computedPembobotan' => $dataPembobotan['computedPembobotan'],
            'pembobotan' => $dataPembobotan['pembobotan'],
            'normalisasi' => $dataPembobotan['normalisasi'],
            'prioritas' => $dataPembobotan['prioritas'],
            'sumNormalisasi' => $dataPembobotan['sumNormalisasi'],
            'sumPrioritas' => $dataPembobotan['sumPrioritas'],
            'pembobotan2' => $dataPembobotan['pembobotan2'],
            'sumPembobotan2' => $dataPembobotan['sumPembobotan2'],
            'hasilPembobotan' => $dataPembobotan['hasilPembobotan'],
            'sumHasilPembobotan' => $dataPembobotan['sumHasilPembobotan'],
            'ci' => $dataPembobotan['ci'],
            'cr' => $dataPembobotan['cr'],
            'tablePembobotanLmu' => $dataPembobotan['tablePembobotanLmu'],
            'rataRataGeoFuzzy' => $dataPembobotan['rataRataGeoFuzzy'],
            'sumPemangkatanIh' => $dataPembobotan['sumPemangkatanIh'],
            'inversPemangkatanIh' => $dataPembobotan['inversPemangkatanIh'],
            'transform' => $dataPembobotan['transform'],
            'defuzzifikasi' => $dataPembobotan['defuzzifikasi'],
        ]);
    }

    public function dataPembobotan()
    {
        $kriteria = Kriteria::all();
        $lastPembobotanCode = Pembobotan::latest()->firstOrNew()->kode;
        $valuePembobotans = ValuePembobotan::whereHas('pembobotan', function ($item) use ($lastPembobotanCode) {
            $item->whereKode($lastPembobotanCode);
        })->get();
        $pembobotan = Pembobotan::whereKode($lastPembobotanCode)->get();
        $computedPembobotan = $kriteria->map(function ($item) use ($kriteria, $valuePembobotans) {
            return $kriteria->map(function ($kri) use ($valuePembobotans, $item) {
                return optional($valuePembobotans->where('x_kriteria_id', $item->id_kri)->where('y_kriteria_id', $kri->id_kri)->first())->value;
            })->toArray();
        });
        $normalisasi = $kriteria->map(function ($item, $itemKey) use ($kriteria, $valuePembobotans, $pembobotan) {
            return $kriteria->map(function ($_item) use ($valuePembobotans, $pembobotan, $item, $itemKey) {
                $value = optional($valuePembobotans->where('x_kriteria_id', $item->id_kri)->where('y_kriteria_id', $_item->id_kri)->first())->value;
                $jumlah = array_key_exists($itemKey,$pembobotan->toArray()) ? $pembobotan->toArray()[$itemKey]['jumlah'] : 1;
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
            return $sumPembobotan2[$iKri] / ($prioritas[$iKri] > 0 ? $prioritas[$iKri] : 1);
        });
        $sumHasilPembobotan = $hasilPembobotan->reduce(function ($carry, $value) {
            return $carry + $value;
        }, 0) / $hasilPembobotan->count();
        $ci = ($sumHasilPembobotan - $kriteriaLength) / ($kriteriaLength - 1);
        $cr = $ci / 1.24;

        $lastPembobotanLmuCode = PembobotanLmu::latest()->firstOrNew()->code;
        $lastPembobotanLmu = PembobotanLmu::whereCode($lastPembobotanLmuCode)->get();
        $tablePembobotanLmu = $lastPembobotanLmu->groupBy('y_kriteria_id')->map(function ($item) {
            return $item->mapWithKeys(function ($_item) {
                return [$_item->x_kriteria_id => $_item->toArray()];
            })->toArray();
        })->toArray();

        $rataRataGeoFuzzy = $kriteria->map(function ($item) use ($tablePembobotanLmu, $kriteriaLength) {
            return [
                'l' => pow(collect($tablePembobotanLmu[$item->id_kri])->reduce(function ($carry, $_item) {
                    return $carry * $_item['l'];
                }, 1), (1 / $kriteriaLength)),
                'm' => pow(collect($tablePembobotanLmu[$item->id_kri])->reduce(function ($carry, $_item) {
                    return $carry * $_item['m'];
                }, 1), (1 / $kriteriaLength)),
                'u' => pow(collect($tablePembobotanLmu[$item->id_kri])->reduce(function ($carry, $_item) {
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
            'l' => 1 / $sumPemangkatanIh['l'],
            'm' => 1 / $sumPemangkatanIh['m'],
            'u' => 1 / $sumPemangkatanIh['u'],
        ];

        $transform = $rataRataGeoFuzzy->map(function ($item, $i) use ($inversPemangkatanIh, $kriteria) {
            return [
                'id_kri' => $kriteria[$i]->id_kri,
                'l' => $item['l'] * $inversPemangkatanIh['l'],
                'm' => $item['m'] * $inversPemangkatanIh['m'],
                'u' => $item['u'] * $inversPemangkatanIh['u'],
            ];
        });

        $defuzzifikasi = $kriteria->map(function ($item, $i) use ($transform) {
            return ((($transform[$i]['u'] - $transform[$i]['l']) + ($transform[$i]['m'] - $transform[$i]['l'])) / 3) + $transform[$i]['l'];
        });

        return [
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
        ];
    }

    public function store(Request $request)
    {
        $form = $request->all();
        $kriteria = Kriteria::all();
        $lastPembobotanCode = Pembobotan::latest()->firstOrNew()->kode;
        $nextPembobotanCode = ($lastPembobotanCode > 0 ? $lastPembobotanCode : 0) + 1;
        foreach ($kriteria as $key => $item) {
            $pembobotan = $item->pembobotans()->create([
                'kode' => $nextPembobotanCode,
                'jumlah' => $form['total'][$key]
            ]);
            foreach ($kriteria as $y => $yItem) {
                $pembobotan->valuePembobotans()->updateOrCreate([
                    'x_kriteria_id' => $item->id_kri,
                    'y_kriteria_id' => $yItem->id_kri,
                ], [
                    'value' => $form['value'][$key][$y]
                ]);
            }
        }
        return back();
    }

    public function storeLmu(Request $request)
    {
        $form = collect($request->all()['pembobotan']);
        $pembobotanLmu = [];
        $lastCode = intval(PembobotanLmu::latest()->firstOrNew()->code);
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
                ];
            }
        }
        PembobotanLmu::insert($pembobotanLmu);
        return back();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Masyarakat;
use App\Models\PembobotanAlternatif;
use Illuminate\Http\Request;

class HasilAkhirController extends Controller
{
    public function index(Request $request)
    {
        abort(500);
        /** Semua Kriteria */
        $kriteria = Kriteria::all();

        /** Sisipan pembobotan ditiap kriteria */
        $kriteria = $kriteria->map(function ($item) {
            $item->pembobotan = KriteriaController::pembobotanByKriteria($item->id_kri)->getData();
            return $item;
        });

        /** Semua Masyarakat */
        $masyarakat = Masyarakat::all();

        $bobotAlternatif = collect();

        /** Buat Array of Object untuk bobot alternatif dengan data default L-M-U 0-0-0 */
        foreach ($kriteria as $item) {
            foreach ($masyarakat as $masyarakatItem) {
                foreach ($item->subKriteria as $subKri) {
                    $bobotAlternatif->push([
                        'masyarakat_id' => $masyarakatItem->id_ms,
                        'sub_kriteria_id' => $subKri->id_sub,
                        'l' => 0,
                        'm' => 0,
                        'u' => 0,
                    ]);
                }
            }
        }
        /** Ambil data pembobotan alternatif berdasarkan data masyarakat dan kriteria */
        $_bobotAlternatif = PembobotanAlternatif::whereIn('masyarakat_id', $bobotAlternatif->pluck('masyarakat_id'))->whereIn('sub_kriteria_id', $bobotAlternatif->pluck('sub_kriteria_id'))->get();
        /** Mapping Data Pembobotan alternatif di tiap Masyarakat dan Tiap Sub Kriteria */
        $bobotAlternatif = $bobotAlternatif->map(function ($bobotAltItem) use ($_bobotAlternatif) {
            $existing = $_bobotAlternatif->where('masyarakat_id', $bobotAltItem['masyarakat_id'])->where('sub_kriteria_id', $bobotAltItem['sub_kriteria_id'])->first();
            return [
                'masyarakat_id' => $bobotAltItem['masyarakat_id'],
                'sub_kriteria_id' => $bobotAltItem['sub_kriteria_id'],
                'l' => optional($existing)->l ?? 0,
                'm' => optional($existing)->m ?? 0,
                'u' => optional($existing)->u ?? 0,
            ];
        });

        $hasilAlternatif = collect();
        /** Kalkulasi / Perhitungan hasil alternatif berdasarkan kesesuaian nilai variable linguistik di tiap masyarakat dengan data bobot alternatif */
        foreach ($masyarakat as $masyarakatItem) {
            $__bobotAlternatif = collect($bobotAlternatif)->where('masyarakat_id', $masyarakatItem->id_ms)->all();
            $l = 0;
            $m = 0;
            $u = 0;
            foreach ($kriteria as $itemKriteria) {
                foreach ($itemKriteria->subKriteria as $i => $itemSubKriteria) {
                    $l += (array_key_exists($i, $itemKriteria->pembobotan['wSubKriteria']->toArray()) ? $itemKriteria->pembobotan['wSubKriteria'][$i]['l'] : 0) * (array_key_exists($itemSubKriteria->id_sub, $__bobotAlternatif) ? $__bobotAlternatif[$itemSubKriteria->id_sub]['l'] : 0);
                    $m += (array_key_exists($i, $itemKriteria->pembobotan['wSubKriteria']->toArray()) ? $itemKriteria->pembobotan['wSubKriteria'][$i]['m'] : 0) * (array_key_exists($itemSubKriteria->id_sub, $__bobotAlternatif) ? $__bobotAlternatif[$itemSubKriteria->id_sub]['m'] : 0);
                    $u += (array_key_exists($i, $itemKriteria->pembobotan['wSubKriteria']->toArray()) ? $itemKriteria->pembobotan['wSubKriteria'][$i]['u'] : 0) * (array_key_exists($itemSubKriteria->id_sub, $__bobotAlternatif) ? $__bobotAlternatif[$itemSubKriteria->id_sub]['u'] : 0);
                }
            }
            $hasilAlternatif->push([
                'l' => $l,
                'm' => $m,
                'u' => $u,
            ]);
        }

        /** Kalulasi dan Pengurutan hasil alternatif teratas */
        $hasilAlternatif = $hasilAlternatif->sortByDesc(function ($item) {
            return $item['l'] + $item['m'] + $item['u'];
        });

        /** Kalkulasi dan Pengurutan defuzifikasi teratas  */
        $defuzzifikasi = $hasilAlternatif->map(function ($item) {
            return (($item['u'] - $item['l']) + ($item['m'] - $item['l']) / 3) + $item['l'];
        })->sortByDesc(function ($item) {
            return $item;
        });

        /** Tampilkan Halaman beserta data yang telah diproses di atas */
        return view('hasil-akhir.index', [
            'judul' => 'Hasil Akhir',
            'kriteria' => $kriteria,
            'masyarakat' => $masyarakat,
            'bobotAlternatif' => $bobotAlternatif,
            'hasilAlternatif' => $hasilAlternatif,
            'defuzzifikasi' => $defuzzifikasi
        ]);
    }

    public function store(Request $request)
    {
        $form = $request->bobotAlternatif;
        PembobotanAlternatif::updatePembobotan($form);
        return back();
    }
}

@extends('template/temp')

@section('li')
<ul class="nav menu">
    <li><a href="/"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
<li><a href="{{url('/list-masyarakat')}}"><em class="fa fa-calendar">&nbsp;</em> Data Masyarakat</a></li>
    <li><a href="{{url('/kriteria')}}"><em class="fa fa-calendar">&nbsp;</em> Data Kriteria</a></li>
    {{--<li><a href="{{route('pkh-record.index')}}"><em class="fa fa-toggle-off">&nbsp;</em> Data PKH</a></li>--}}
    <li class="active"><a href="{{route('pembobotan.index')}}"><em class="fa fa-toggle-off">&nbsp;</em> Pembobotan</a></li>
    <li><a href="{{route('hasil-akhir.index')}}"><em class="fa fa-file">&nbsp;</em> Hasil Akhir</a></li>
</ul>
@endsection

@section('header')
<div class="row">
    <ol class="breadcrumb">
        <li><a href="#">
                <em class="fa fa-home"></em>
            </a></li>
        <li class="active">{{$judul}}</li>
    </ol>
</div>
<!--/.row-->

<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">{{$judul}}</h1>
    </div>
</div>
<!--/.row-->

@endsection


@section('konten')
<div>
    <div class="panel panel-default" x-data="{
        computedPembobotan : ({{json_encode($computedPembobotan)}}),
        setBobot(event, x, y){
            this.computedPembobotan[x][y] = event.target.value
        },
        get jumlah(){
            return this.computedPembobotan.map(item => item.reduce((carry, item) => carry + parseFloat(item), 0))
        }
    }">
        <div class="panel-body">
            <div class="col-md-12">
                @if(session('input'))
                <div class="col-lg-6 alert alert-success">
                    {{ session('input') }}
                </div>
                @endif
            </div>
            <div class="col-md-12">
                <div class="table-responsive">
                    <form action="{{route('kriteria.storePembobotan', request('kriteria'))}}" x-data="{
                    
                }" method="POST">
                        @csrf
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Kriteria</th>
                                    @foreach ($kriteria as $item)
                                    <th>{{$item->kriteria}}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kriteria as $y => $item)
                                <tr>
                                    <td>{{$item->kriteria}}</td>
                                    @foreach ($kriteria as $x => $_item)
                                    <td><input name="value[{{$x}}][{{$y}}]" style="min-width:84px" class="form-control" type="number" step="0.00000000001" x-bind:value="computedPembobotan['{{$x}}']['{{$y}}']" x-on:change="setBobot($event, '{{$x}}', '{{$y}}')"></td>
                                    @endforeach
                                </tr>
                                @endforeach
                                <tr>
                                    <td>Jumlah</td>
                                    <template x-for="item in jumlah">
                                        <td><input class="form-control" readonly x-bind:value="item" x-bind:name="`total[]`"></td>
                                    </template>
                                </tr>
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Hitung</button>
                    </form>
                </div>
            </div>
        </div>
    </div><!-- /.panel-->
    <div class="panel panel-default mt-3" x-data="{
    }">
        <h1 style="padding:28px">Normalisasi</h1>
        <div class="panel-body">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kriteria</th>
                                @foreach ($kriteria as $item)
                                <th>{{$item->kriteria}}</th>
                                @endforeach
                                <th>Prioritas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kriteria as $y => $item)
                            <tr>
                                <td>{{$item->kriteria}}</td>
                                @foreach ($kriteria as $x => $_item)
                                <td>{{json_encode($normalisasi[$x][$y])}}</td>
                                @endforeach
                                <td>{{$prioritas[$y]}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td>Jumlah</td>
                                @foreach ($sumNormalisasi as $item)
                                <td>{{$item}}</td>
                                @endforeach
                                <td>{{$sumPrioritas}}</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Kriteria</th>
                                @foreach ($kriteria as $item)
                                <th>{{$item->kriteria}}</th>
                                @endforeach
                                <th>Jumlah</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kriteria as $y => $item)
                            <tr>
                                <td>{{$item->kriteria}}</td>
                                @foreach ($kriteria as $x => $_item)
                                <td>{{$pembobotan2[$x][$y]}}</td>
                                @endforeach
                                <td>{{$sumPembobotan2[$y]}}</td>
                                <td>{{$hasilPembobotan[$y]}}</td>
                            </tr>
                            @endforeach
                            {{-- <template x-for="(item, y) in kriteria">
                                <tr>
                                    <td x-text="item.kriteria"></td>
                                    <template x-for="(_item, x) in kriteria">
                                        <td><input style="min-width:84px" class="form-control" type="text" x-bind:value="pembobotan2[x][y]" readonly></td>
                                    </template>
                                    <td><input style="min-width:84px" class="form-control" type="text" x-bind:value="jumlah[y]" readonly></td>
                                    <td><input style="min-width:84px" class="form-control" type="text" x-bind:value="hasil[y]" readonly></td>
                                </tr>
                            </template> --}}
                            <tr>
                                <td colspan="{{$normalisasi->count() + 2}}">Avg</td>
                                <td>{{$sumHasilPembobotan}}</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>CI=</td>
                                <td>{{$ci}}</td>
                            </tr>
                            <tr>
                                <td>CR=</td>
                                <td>{{$cr}}</td>
                            </tr>
                        </tbody>
                    </table>

                    <form action="{{route('kriteria.storePembobotanLmu', request('kriteria'))}}" method="post">
                        @csrf
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td rowspan="2">Kriteria</td>
                                    @foreach ($kriteria as $item)
                                    <td colspan="3">{{$item->kriteria}}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($kriteria as $item)
                                    <td width="100">L</td>
                                    <td width="100">M</td>
                                    <td width="100">U</td>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kriteria as $y => $item)
                                <tr>
                                    <td>{{$item->kriteria}}</td>
                                    @foreach ($kriteria as $x => $_item)
                                    <td>
                                        <input style="width:100px" type="number" step="0.00000000001" class="form-control" name="pembobotan[{{$item->id_sub}}][{{$_item->id_sub}}][l]" value="{{array_key_exists($item->id_sub, $tablePembobotanLmu) ? $tablePembobotanLmu[$item->id_sub][$_item->id_sub]['l'] : 0}}">
                                    </td>
                                    <td>
                                        <input style="width:100px" type="number" step="0.00000000001" class="form-control" name="pembobotan[{{$item->id_sub}}][{{$_item->id_sub}}][m]" value="{{array_key_exists($item->id_sub, $tablePembobotanLmu) ? $tablePembobotanLmu[$item->id_sub][$_item->id_sub]['m'] : 0}}">
                                    </td>
                                    <td>
                                        <input style="width:100px" type="number" step="0.00000000001" class="form-control" name="pembobotan[{{$item->id_sub}}][{{$_item->id_sub}}][u]" value="{{array_key_exists($item->id_sub, $tablePembobotanLmu) ? $tablePembobotanLmu[$item->id_sub][$_item->id_sub]['u'] : 0}}">
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary">Hitung</button>
                    </form>

                    <table class="table table-bordered" style="margin-top: 20px">
                        <thead>
                            <tr>
                                <td rowspan="2">Kriteria</td>
                                <td colspan="3">{{optional($kriteria->first())->kriteria}}</td>
                            </tr>
                            <tr>
                                <td>L</td>
                                <td>M</td>
                                <td>U</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kriteria as $i => $item)
                            <tr>
                                <td>{{$item->kriteria}}</td>
                                <td>{{json_encode($rataRataGeoFuzzy[$i]['l'])}}</td>
                                <td>{{json_encode($rataRataGeoFuzzy[$i]['m'])}}</td>
                                <td>{{json_encode($rataRataGeoFuzzy[$i]['u'])}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td>Jumlah</td>
                                <td>{{$sumPemangkatanIh['l']}}</td>
                                <td>{{$sumPemangkatanIh['m']}}</td>
                                <td>{{$sumPemangkatanIh['u']}}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="my-2">
                        <h4>Invers</h4>
                    </div>

                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <td>Invers</td>
                                <td>{{$inversPemangkatanIh['l']}}</td>
                                <td>{{$inversPemangkatanIh['m']}}</td>
                                <td>{{$inversPemangkatanIh['u']}}</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td rowspan="2">Kriteria</td>
                                <td colspan="3">{{optional($kriteria->first())->kriteria}}</td>
                            </tr>
                            <tr>
                                <td>L</td>
                                <td>M</td>
                                <td>U</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kriteria as $key => $item)
                            <tr>
                                <td>{{$item->kriteria}}</td>
                                <td>{{$transform[$key]['l']}}</td>
                                <td>{{$transform[$key]['m']}}</td>
                                <td>{{$transform[$key]['u']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h4>W Kriteria</h4>

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td rowspan="2">Kriteria</td>
                                <td colspan="3">{{$parentKriteria->nama_kri}}</td>
                            </tr>
                            <tr>
                                <td>L</td>
                                <td>M</td>
                                <td>U</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{$parentKriteria->nama_kri}}</td>
                                <td>{{$wKriteria['l']}}</td>
                                <td>{{$wKriteria['m']}}</td>
                                <td>{{$wKriteria['u']}}</td>
                            </tr>
                        </tbody>
                    </table>


                    <h4>W SUB {{$parentKriteria->nama_kri}}</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td rowspan="2">Kriteria</td>
                                <td colspan="3">{{$parentKriteria->nama_kri}}</td>
                            </tr>
                            <tr>
                                <td>L</td>
                                <td>M</td>
                                <td>U</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kriteria as $i => $item)
                            <tr>
                                <td>{{$item->kriteria}}</td>
                                <td>{{$wSubKriteria[$i]['l']}}</td>
                                <td>{{$wSubKriteria[$i]['m']}}</td>
                                <td>{{$wSubKriteria[$i]['u']}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h4>Defuzzifikasi</h5>
                        <table class="table table-bordered">
                            <tbody>
                                @foreach ($kriteria as $i => $item)
                                <tr>
                                    <td>{{$item->kriteria}}</td>
                                    <td>{{$defuzzifikasi[$i]}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div><!-- /.panel-->
</div>
@endsection
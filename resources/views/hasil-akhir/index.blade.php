@extends('template/temp')

@section('li')
<ul class="nav menu">
	<li><a href="/"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
	<li><a href="{{url('/masyarakat')}}"><em class="fa fa-calendar">&nbsp;</em> Data Masyarakat</a></li>
	<li><a href="{{url('/kriteria')}}"><em class="fa fa-calendar">&nbsp;</em> Data Kriteria</a></li>
	{{--<li><a href="{{route('pkh-record.index')}}"><em class="fa fa-toggle-off">&nbsp;</em> Data PKH</a></li>--}}
	<li><a href="{{route('pembobotan.index')}}"><em class="fa fa-toggle-off">&nbsp;</em> Pembobotan</a></li>
	<li class="active"><a href="{{route('hasil-akhir.index')}}"><em class="fa fa-file">&nbsp;</em> Hasil Akhir</a></li>
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

<div class="panel panel-default" x-data="{
	bobotAlternatif : {{json_encode($bobotAlternatif)}}
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
				<table class="table table-bordered">
					<thead>
						<tr>
							<th rowspan="2">Kriteria/Sub</th>
							<th colspan="3">Bobot Lokal/transform</th>
							<th colspan="3">Bobot Keseluruhan</th>
							<th rowspan="2">BNP</th>
						</tr>
						<tr>
							<th>L</th>
							<th>M</th>
							<th>U</th>
							<th>L</th>
							<th>M</th>
							<th>U</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($kriteria as $item)
						<tr>
							<th colspan="7">{{$item->nama_kri}}</th>
							<td>-</td>
						</tr>
						@foreach ($item->pembobotan['transform'] as $i => $transform)
						<tr>
							<td>{{$i+1}}</td>
							<td>{{$transform['l']}}</td>
							<td>{{$transform['m']}}</td>
							<td>{{$transform['u']}}</td>
							<td>{{$item->pembobotan['wSubKriteria'][$i]['l']}}</td>
							<td>{{$item->pembobotan['wSubKriteria'][$i]['m']}}</td>
							<td>{{$item->pembobotan['wSubKriteria'][$i]['u']}}</td>
							<td>-</td>
						</tr>
						@endforeach
						@endforeach
					</tbody>
				</table>
			</div>
			<div class="table-responsive">
				<h4>Bobot Alternatif Pada Kriteria</h4>
				<form action="{{route('hasil-akhir.store')}}" method="POST">
					@csrf
					@foreach ($kriteria as $kriI => $item)
					<h5>{{$item->nama_kri}}</h5>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th rowspan="2">Nama</th>
								<th rowspan="2">Alternatif</th>
								@foreach ($item->subKriteria as $subKriItem)
								<th colspan="3">{{$subKriItem->kriteria}}</th>
								@endforeach
							</tr>
							@foreach ($item->subKriteria as $subKriItem)
							<th>L</th>
							<th>M</th>
							<th>U</th>
							@endforeach
						</thead>
						<tbody>
							@foreach ($masyarakat as $i => $masyarakatItem)
							<tr>
								<td style="white-space: nowrap">{{$masyarakatItem->nama}}</td>
								<td>A{{$i+1}}</td>
								@foreach ($item->subKriteria as $subKriItem)
								<td><input name="bobotAlternatif[{{$masyarakatItem->id_ms}}][{{$subKriItem->id_sub}}][l]" x-bind:value="bobotAlternatif.find(item => item.masyarakat_id == {{$masyarakatItem->id_ms}} && item.sub_kriteria_id == {{$subKriItem->id_sub}})?.l" x-on:change="bobotAlternatif.find(item => item.masyarakat_id == {{$masyarakatItem->id_ms}} && item.sub_kriteria_id == {{$subKriItem->id_sub}}).l = $event.target.value" style="width:100px" type="number"></td>
								<td><input name="bobotAlternatif[{{$masyarakatItem->id_ms}}][{{$subKriItem->id_sub}}][m]" x-bind:value="bobotAlternatif.find(item => item.masyarakat_id == {{$masyarakatItem->id_ms}} && item.sub_kriteria_id == {{$subKriItem->id_sub}})?.m" x-on:change="bobotAlternatif.find(item => item.masyarakat_id == {{$masyarakatItem->id_ms}} && item.sub_kriteria_id == {{$subKriItem->id_sub}}).m = $event.target.value" style="width:100px" type="number"></td>
								<td><input name="bobotAlternatif[{{$masyarakatItem->id_ms}}][{{$subKriItem->id_sub}}][u]" x-bind:value="bobotAlternatif.find(item => item.masyarakat_id == {{$masyarakatItem->id_ms}} && item.sub_kriteria_id == {{$subKriItem->id_sub}})?.u" x-on:change="bobotAlternatif.find(item => item.masyarakat_id == {{$masyarakatItem->id_ms}} && item.sub_kriteria_id == {{$subKriItem->id_sub}}).u = $event.target.value" style="width:100px" type="number"></td>
								@endforeach
							</tr>
							@endforeach
						</tbody>
					</table>
					@endforeach
					<button type="submit" class="btn btn-primary">Hitung</button>
				</form>
			</div>

			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<td>Nama</td>
							<td>Alternatif</td>
							<td>L</td>
							<td>M</td>
							<td>U</td>
						</tr>
					</thead>
					<tbody>
						@foreach ($hasilAlternatif as $i => $item)
						<tr>
							<td>{{$masyarakat[$i]->nama}}</td>
							<td>A{{$i+1}}</td>
							<td>{{$item['l']}}</td>
							<td>{{$item['m']}}</td>
							<td>{{$item['u']}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<h4>Defuzifikasi Untuk mendapatkan nilai BNP</h4>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<td>Nama</td>
							<td>Alternatif</td>
						</tr>
					</thead>
					<tbody>
						@foreach ($defuzzifikasi as $i => $item)
						<tr>
							<td>{{$masyarakat[$i]->nama}}</td>
							<td>A{{$i+1}}</td>
							<td>{{$item}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div><!-- /.panel-->
@endsection
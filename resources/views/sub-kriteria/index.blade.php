@extends('template/temp')

@section('li')
<ul class="nav menu">
	<li><a href="/"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
<li><a href="{{url('/list-masyarakat')}}"><em class="fa fa-calendar">&nbsp;</em> Data Masyarakat</a></li>
	<li class="active"><a href="{{url('/kriteria')}}"><em class="fa fa-calendar">&nbsp;</em> Data Kriteria</a></li>
	{{--<li><a href="{{route('pkh-record.index')}}"><em class="fa fa-toggle-off">&nbsp;</em> Data PKH</a></li>--}}
	<li><a href="{{route('pembobotan.index')}}"><em class="fa fa-toggle-off">&nbsp;</em> Pembobotan</a></li>
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

<div class="panel panel-default">
	<div class="panel-heading">
		<a href="{{route('sub-kriteria.create', $kriteria->id_kri)}}" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
			<i class="fa fa-plus">
				Tambah Data
			</i>
		</a>
	</div>
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
				<table id="example1" class="table table-bordered table-striped">
					<thead>
						<tr>
							<th rowspan="2">NO</th>
<th rowspan="2">NAMA KRI</th>
							<th rowspan="2">#</th>
</tr>
					</thead>
					<tbody>
						@foreach($data as $key => $item)
						<tr>
							<td>{{$key+1}}</td>
<td>{{$item->kriteria}}</td>
							<td>
								<div style="display:flex;gap:6px">
									<a href="#" data-id="{{ $item->id_sub}}" data-toggle="modal" data-target="#editModal{{$item->id_sub}}" class="btn btn-sm btn-warning btn-edit" title="Edit">
										Ubah
									</a>
									<div class="modal fade" id="editModal{{$item->id_sub}}" tabindex="-1" role="dialog" aria-labelledby="editModal{{$item->id_sub}}Label" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="editModal{{$item->id_sub}}Label">Tambah </h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<form action="{{url('kriteria/'.$kriteria->id_kri.'/sub-kriteria/'.$item->id_sub)}}" method="post">
													@csrf
													@method('PUT')
													<div class="modal-body">
														<div class="modal-body">
															<div class="form-group">
																<label>Nama Kriteria</label>
																<input class="form-control" name="kriteria" value="{{$item->kriteria}}" placeholder="Nama Kriteria">
															</div>
															<div class="form-group">
																<label for="input_dari">Dari</label>
																<input class="form-control" name="dari" type="number" value="{{$item->dari}}" placeholder="Rentang Nilai Dari">
															</div>
															<div class="form-group">
																<label for="input_sampai">Sampai</label>
																<input class="form-control" name="sampai" type="number" value="{{$item->sampai}}" placeholder="Rentang Nilai Sampai">
															</div>
														</div>
														<div class="modal-footer">
															<button type="submit" class="btn btn-primary">Update</button>
															<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
									{{-- <form method="post"> --}}
										<form method="post" action="{{url('kriteria/'.$kriteria->id_kri.'/sub-kriteria/'.$item->id_sub)}}">
											@method('delete')
											@csrf
											<button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data?')"> Hapus</button>
										</form>
								</div>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>



		</div>
	</div>
</div><!-- /.panel-->
@endsection

@section('modal')
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tambah </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form action="{{ route('sub-kriteria.store', $kriteria->id_kri) }}" method="post">
				@csrf
				<div class="modal-body">
					<div class="modal-body">
						<div class="form-group">
							<label>Kriteria</label>
							<input class="form-control" name="kriteria" placeholder="Nama Kriteria">
						</div>
						<div class="form-group">
							<label for="input_dari">Dari</label>
							<input class="form-control" type="number" name="dari" placeholder="Rentang Nilai Dari" id="input_dari">
						</div>
						<div class="form-group">
							<label for="input_sampai">Sampai</label>
							<input class="form-control" type="number" name="sampai" placeholder="Rentang Nilai Sampai" id="input_sampai">
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Tambah</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
					</div>
			</form>
		</div>
	</div>
</div>
@endsection
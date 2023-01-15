@extends('template/temp')

@section('li')
<ul class="nav menu">
	<li><a href="/"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
	<li><a href="{{url('/masyarakat')}}"><em class="fa fa-calendar">&nbsp;</em> Data Masyarakat</a></li>
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
		<a href="{{url('kriteria.create')}}" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
			<i class="fa fa-plus">
				Tambah Data
			</i>
		</a>
		<a href="{{route('kriteria.export')}}" target="_blank" class="btn btn-warning">
			<i class="fa fa-download">
				Download Data
			</i>
		</a>
		<button class="btn btn-info" data-toggle="modal" data-target="#uploadModal">
			<i class="fa fa-upload">
				Import Data
			</i>
		</button>
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
							<th>No</th>
							<th>KODE KRI</th>
							<th>NAMA KRI</th>
						</tr>
					</thead>
					<tbody>
						@foreach($data as $key => $item)
						<tr>
							<td>{{$key+1}}</td>
							<td>{{$item->kode_kri}}</td>
							<td>{{$item->nama_kri}}</td>
							<td>
								<div style="display:flex;gap:6px">
									<a href="#" data-id="{{ $item->id_kri}}" data-toggle="modal" data-target="#editModal{{$item->id_kri}}" class="btn btn-sm btn-warning btn-edit" title="Edit">
										Ubah
									</a>
									<div class="modal fade" id="editModal{{$item->id_kri}}" tabindex="-1" role="dialog" aria-labelledby="editModal{{$item->id_kri}}Label" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="editModal{{$item->id_kri}}Label">Tambah </h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<form action="{{ route('kriteria.update', $item->id_kri) }}" method="post">
													@csrf
													@method('PUT')
													<div class="modal-body">
														<div class="modal-body">
															<div class="form-group">
																<label>Kode Kriteria</label>
																<input type="number" class="form-control" name="kode_kri" value="{{$item->kode_kri}}" placeholder="Kode Kriteria">
															</div>
															<div class="form-group">
																<label>Nama Kriteria</label>
																<input class="form-control" name="nama_kri" value="{{$item->nama_kri}}" placeholder="Nama Kriteria">
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
									<form method="post" action="{{route('kriteria.destroy', $item->id_kri)}}">
										@method('delete')
										@csrf
										<button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data?')"> Hapus</button>
									</form>
									<a class="btn btn-info" href="{{route('sub-kriteria.index', $item->id_kri)}}">Sub Kriteria</a>
									<a class="btn btn-success" href="{{route('kriteria.pembobotan', $item->id_kri)}}">Pembobotan</a>
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

<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="uploadModalLabel">Upload </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form action="{{ route('kriteria.import') }}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="modal-body">
					<div class="modal-body">
						<div class="form-group">
							<label for="excel_input">File Excel</label>
							<input type="file" name="excel" id="excel_input" class="form-control">
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-primary">Upload</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
					</div>
			</form>
		</div>
	</div>
</div>
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

			<form action="{{ route('kriteria.store') }}" method="post">
				@csrf
				<div class="modal-body">
					<div class="modal-body">
						<div class="form-group">
							<label>Kode Kriteria</label>
							<input type="number" class="form-control" name="kode_kri" placeholder="Kode Kriteria">
						</div>
						<div class="form-group">
							<label>Nama Kriteria</label>
							<input class="form-control" name="nama_kri" placeholder="Nama Kriteria">
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
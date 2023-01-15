@extends('template/temp')

@section('li')
<ul class="nav menu">
	<li><a href="/"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
	<li><a href="{{url('/masyarakat')}}"><em class="fa fa-calendar">&nbsp;</em> Data Masyarakat</a></li>
	<li><a href="{{url('/kriteria')}}"><em class="fa fa-calendar">&nbsp;</em> Data Kriteria</a></li>
	<li class="active"><a href="{{route('pkh-record.index')}}"><em class="fa fa-toggle-off">&nbsp;</em> Data PKH</a></li>
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
		<a href="{{route('pkh-record.create')}}" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
			<i class="fa fa-plus">
				Tambah Data
			</i>
		</a>
		<a href="{{route('pkh-record.export')}}" target="_blank" class="btn btn-warning">
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
							<th>NAMA PENERIMA</th>
							<th>NIK KTP</th>
							<th>TAHAP</th>
							<th>BANSOS</th>
							<th>IDSEMESTA</th>
							<th>NO KEL</th>
							<th>NM PROV</th>
							<th>NM KAB</th>
							<th>NM KEC</th>
							<th>NM KEL</th>
							<th>ALAMAT</th>
							<th>UMUR</th>
							<th>TANGGUNGAN</th>
							<th>STATUS</th>
							<th>PENGHASILAN</th>
						</tr>
					</thead>
					<tbody>
						@foreach($data as $key => $item)
						<tr>
							<td class="text-nowrap">{{$key+1}}</td>
							<td class="text-nowrap">{{$item->masyarakat->nama}}</td>
							<td class="text-nowrap">{{$item->masyarakat->nik}}</td>
							<td class="text-nowrap">{{$item->tahap}}</td>
							<td class="text-nowrap">{{$item->bansos}}</td>
							<td class="text-nowrap">{{$item->idsemesta}}</td>
							<td class="text-nowrap">{{$item->no_kel}}</td>
							<td class="text-nowrap">{{$item->nm_prov}}</td>
							<td class="text-nowrap">{{$item->nm_kab}}</td>
							<td class="text-nowrap">{{$item->nm_kec}}</td>
							<td class="text-nowrap">{{$item->nm_kel}}</td>
							<td class="text-nowrap">{{$item->alamat}}</td>
							<td class="text-nowrap">{{$item->umur}}</td>
							<td class="text-nowrap">{{$item->tanggungan}}</td>
							<td class="text-nowrap">{{$item->status}}</td>
							<td class="text-nowrap">{{$item->penghasilan}}</td>
							<td class="text-nowrap">
								<div style="display: flex; gap:6px">
									<a href="#" data-id="{{ $item->id}}" data-toggle="modal" data-target="#editModal{{$item->id}}" class="btn btn-sm btn-warning btn-edit" title="Edit">
										Ubah
									</a>
									<div class="modal fade" id="editModal{{$item->id}}" tabindex="-1" role="dialog" aria-labelledby="editModal{{$item->id}}Label" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<h5 class="modal-title" id="editModal{{$item->id}}Label">Tambah </h5>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
														<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<form action="{{ route('pkh-record.update', $item->id) }}" method="post">
													@csrf
													@method('PUT')
													<div class="modal-body">
														<div class="modal-body">
															<div class="form-group">
																<label>Masyarakat</label>
																<select class="form-control" name="masyarakat_id">
																	@foreach ($masyarakat as $masyarakatItem)
																	<option value="{{$masyarakatItem->id_ms}}" {{$masyarakatItem->id_ms == $item->masyarakat_id}}>{{$masyarakatItem->nama}}</option>
																	@endforeach
																</select>
															</div>
															<div class="form-group">
																<label>Tahap </label>
																<input type="number" class="form-control" name="tahap" value="{{$item->tahap}}" placeholder="Tahap">
															</div>
															<div class="form-group">
																<label>Bansos </label>
																<input class="form-control" name="bansos" value="{{$item->bansos}}" placeholder="Bansos">
															</div>
															<div class="form-group">
																<label>Idsemesta </label>
																<input class="form-control" name="idsemesta" value="{{$item->idsemesta}}" placeholder="Idsemesta">
															</div>
															<div class="form-group">
																<label>No Kel </label>
																<input class="form-control" name="no_kel" value="{{$item->no_kel}}" placeholder="No Kel">
															</div>
															<div class="form-group">
																<label>Nm Prov </label>
																<input class="form-control" name="nm_prov" value="{{$item->nm_prov}}" placeholder="Nm Prov">
															</div>
															<div class="form-group">
																<label>Nm Kab </label>
																<input class="form-control" name="nm_kab" value="{{$item->nm_kab}}" placeholder="Nm Kab">
															</div>
															<div class="form-group">
																<label>Nm Kec </label>
																<input class="form-control" name="nm_kec" value="{{$item->nm_kec}}" placeholder="Nm Kec">
															</div>
															<div class="form-group">
																<label>Nm Kel </label>
																<input class="form-control" name="nm_kel" value="{{$item->nm_kel}}" placeholder="Nm Kel">
															</div>
															<div class="form-group">
																<label>Alamat </label>
																<textarea class="form-control" name="alamat" placeholder="Alamat">{{$item->alamat}}</textarea>
															</div>
															<div class="form-group">
																<label>Umur </label>
																<input type="number" class="form-control" name="umur" value="{{$item->umur}}" placeholder="Umur">
															</div>
															<div class="form-group">
																<label>Tanggungan </label>
																<input type="number" class="form-control" name="tanggungan" value="{{$item->tanggungan}}" placeholder="Tanggungan">
															</div>
															<div class="form-group">
																<label>Status </label>
																<input class="form-control" name="status" value="{{$item->status}}" placeholder="Status">
															</div>
															<div class="form-group">
																<label>Penghasilan </label>
																<input type="number" class="form-control" name="penghasilan" value="{{$item->penghasilan}}" placeholder="Penghasilan">
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
									<form method="post" action="{{route('pkh-record.destroy', $item->id)}}">
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

<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="uploadModalLabel">Upload </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form action="{{ route('pkh-record.import') }}" method="post" enctype="multipart/form-data">
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

			<form action="{{ route('pkh-record.store') }}" method="post">
				@csrf
				<div class="modal-body">
					<div class="modal-body">
						<div class="form-group">
							<label>Masyarakat</label>
							<select class="form-control" name="masyarakat_id">
								@foreach ($masyarakat as $masyarakatItem)
								<option value="{{$masyarakatItem->id_ms}}">{{$masyarakatItem->nama}}</option>
								@endforeach
							</select>
						</div>
						<div class="form-group">
							<label>Tahap </label>
							<input type="number" class="form-control" name="tahap" placeholder="Tahap">
						</div>
						<div class="form-group">
							<label>Bansos </label>
							<input class="form-control" name="bansos" placeholder="Bansos">
						</div>
						<div class="form-group">
							<label>Idsemesta </label>
							<input class="form-control" name="idsemesta" placeholder="Idsemesta">
						</div>
						<div class="form-group">
							<label>No Kel </label>
							<input class="form-control" name="no_kel" placeholder="No Kel">
						</div>
						<div class="form-group">
							<label>Nm Prov </label>
							<input class="form-control" name="nm_prov" placeholder="Nm Prov">
						</div>
						<div class="form-group">
							<label>Nm Kab </label>
							<input class="form-control" name="nm_kab" placeholder="Nm Kab">
						</div>
						<div class="form-group">
							<label>Nm Kec </label>
							<input class="form-control" name="nm_kec" placeholder="Nm Kec">
						</div>
						<div class="form-group">
							<label>Nm Kel </label>
							<input class="form-control" name="nm_kel" placeholder="Nm Kel">
						</div>
						<div class="form-group">
							<label>Alamat </label>
							<textarea class="form-control" name="alamat" placeholder="Alamat"> </textarea>
						</div>
						<div class="form-group">
							<label>Umur </label>
							<input type="number" class="form-control" name="umur" placeholder="Umur">
						</div>
						<div class="form-group">
							<label>Tanggungan </label>
							<input type="number" class="form-control" name="tanggungan" placeholder="Tanggungan">
						</div>
						<div class="form-group">
							<label>Status </label>
							<input class="form-control" name="status" placeholder="Status">
						</div>
						<div class="form-group">
							<label>Penghasilan </label>
							<input type="number" class="form-control" name="penghasilan" placeholder="Penghasilan">
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
@extends('template/temp')

@section('li')
<ul class="nav menu">
	<li><a href="/"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
	<li class="active"><a href="{{url('/list-masyarakat')}}"><em class="fa fa-calendar">&nbsp;</em> Data Masyarakat</a></li>
	<li><a href="{{url('/kriteria')}}"><em class="fa fa-calendar">&nbsp;</em> Data Kriteria</a></li>
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
		<a href="masyarakat/tambah" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
			<i class="fa fa-plus">
				Tambah Data
			</i>
		</a>
		<button class="btn btn-info" data-toggle="modal" href='#upload-modal'>
			<i class="fa fa-upload">
				Upload Data
			</i>
		</button>
		<a href="{{url('/masyarakat/download')}}" target="_blank" class="btn btn-warning">
			<i class="fa fa-download">
				Download Data
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
							<th class="text-nowrap">NO</th>
							<th class="text-nowrap">NIK</th>
							<th class="text-nowrap">NAMA</th>
							<th class="text-nowrap">ALAMAT</th>
							<th class="text-nowrap">HAMIL</th>
							<th class="text-nowrap">PENDIDIKAN</th>
							<th class="text-nowrap">TANGGUNGAN</th>
							<th class="text-nowrap">PENGHASILAN</th>
							<th class="text-nowrap">DISSABILITAS</th>
							<th class="text-nowrap">UMUR</th>
							<th class="text-nowrap">#</th>
						</tr>
					</thead>
					<tbody>
						@foreach($data as $key)
						<tr>
							<td class="text-nowrap"> {{ $loop->iteration }} </td>
							<td class="text-nowrap"> {{$key->nik}} </td>
							<td class="text-nowrap"> {{$key->nama}} </td>
							<td class="text-nowrap"> {{$key->alamat}}</td>
							<td class="text-nowrap"> {{$key->hamil}}</td>
							<td class="text-nowrap"> {{$key->pendidikan}}</td>
							<td class="text-nowrap"> {{$key->tanggungan}}</td>
							<td class="text-nowrap"> {{$key->penghasilan}}</td>
							<td class="text-nowrap"> {{$key->dissabilitas}}</td>
							<td class="text-nowrap"> {{$key->umur}}</td>
							<td class="text-nowrap">
								<center>
									<form method="post" action="/masyarakat/{{$key->id_ms}}">
										@method('delete')
										@csrf
										<a href="#" data-id="{{ $key->id_ms}}" data-toggle="modal" data-target="#modalEdit" class="btn btn-sm btn-warning btn-edit" title="Edit">
											Ubah
										</a>
										<button class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data?')"> Hapus</button>
									</form>
								</center>
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

			<form action="{{ url('/masyarakat/tambah') }}" method="post">
				@csrf
				<div class="modal-body">
					<div class="modal-body">
						<div class="form-group">
							<label>NIK </label>
							<input class="form-control" name="nik" placeholder="nik">
						</div>
						<div class="form-group">
							<label>Nama </label>
							<input class="form-control" name="nama" placeholder="nama">
						</div>
						<div class="form-group">
							<label>Alamat</label>
							<input class="form-control" name="alamat" placeholder="Masukkan Alamat">
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
</div>

<!-- Modal edit -->

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEdit" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalEdit">Ubah </h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form action="#" id="form-edit">
				@csrf
				<div class="modal-body">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary btn-update">Ubah</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="upload-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Upload file excel</h4>
			</div>
			<form action="{{route('masyarakat.upload')}}" method="post" enctype="multipart/form-data">
				<div class="modal-body">
					@csrf
					<input type="file" name="excel" class="form-control" accept=".xlsx">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Upload</button>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection

@section('js')

<script>
	$('.btn-edit').on('click', function () {

		let id = $(this).data('id')
		console.log(id)
		$.ajax({
			url: `/masyarakat/${id}/edit`,
			method: "GET",
			success: function (data) {
				$('#modalEdit').find('.modal-body').html(data)
			},
			error: function (error) {
				console.log(error)
			}
		})
	})

    $('.btn-update').on('click',function(){

    let id = $('#form-edit').find('#id').val()
    let data = $('#form-edit').serialize()
    $.ajax({
      url:`/masyarakat/${id}`,
      method:"PUT",
      data: data,
      success: function(data){
        $('#modalEdit').modal('hide')
        window.location.assign('/masyarakat')
      },
      error:function(error){
        console.log(error)
      }
    })
  })
</script>

@endsection
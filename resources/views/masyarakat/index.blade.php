@extends('template/temp')

@section('li')
<ul class="nav menu">
	<li><a href="/"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
	<li class="active"><a href="{{url('/masyarakat')}}"><em class="fa fa-calendar">&nbsp;</em> Data Masyarakat</a></li>
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

			<table id="example1" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>NO</th>
						<th>NIK</th>
						<th>NAMA</th>
						<th>ALAMAT</th>
						@foreach ($kriteria as $kriteriaItem)
						<th>{{$kriteriaItem->nama_kri}}</th>
						@endforeach
					</tr>
				</thead>
				<tbody>
					@foreach($data as $key)
					<tr>
						<td> {{ $loop->iteration }} </td>
						<td> {{$key->nik}} </td>
						<td> {{$key->nama}} </td>
						<td> {{$key->alamat}}</td>
						<td>
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
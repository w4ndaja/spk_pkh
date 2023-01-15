@extends('template/temp')

@section('li')
<ul class="nav menu">
	<li class="active"><a href="/"><em class="fa fa-dashboard">&nbsp;</em> Dashboard</a></li>
	<li><a href="{{url('/masyarakat')}}"><em class="fa fa-calendar">&nbsp;</em> Data Masyarakat</a></li>
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
<div class="row">
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-body d-flex justify-content-center align-items-center">
				<div class="col-xs-3" style="margin-top: 17px;">
					<em class="fa fa-xl fa-database color-blue" style="font-size: 42px;"></em>
				</div>
				<div class="col-xs-9">
					<div class="large">{{$masyarakat}}</div>
					<div class="text-muted">Data Masyarakat</div>
					<div class="text-muted"><a href="{{url('/masyarakat')}}">detail</a></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-3">
		<div class="panel panel-default">
			<div class="panel-body d-flex justify-content-center align-items-center">
				<div class="col-xs-3" style="margin-top: 17px;">
					<em class="fa fa-xl fa-comments color-orange" style="font-size: 42px;"></em>
				</div>
				<div class="col-xs-9">
					<div class="large">{{$kriteria}}</div>
					<div class="text-muted">Data Kriteria</div>
					<div class="text-muted"><a href="{{url('/kriteria')}}">detail</a></div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-body">
				<div class="row">
					<div class="panel panel-blue panel-widget border-right ps-2 pe-2" style="padding-left: 19px;padding-right: 19px;text-align:left;color:black;">
						<h3 class="text-left" style="font-weight:700">PKH adalah</h3>
						<div>
							<p style="color:black">Program Keluarga Harapan (PKH) adalah program pemberian bantuan sosial bersyarat kepada Keluarga Miskin (KM) yang ditetapkan sebagai keluarga penerima manfaat PKH.
								Sebagai upaya percepatan penanggulangan kemiskinan, sejak tahun 2007 Pemerintah Indonesia telah melaksanakan PKH. ProgramPerlindungan Sosial yang juga dikenal di dunia internasional dengan istilah Conditional Cash Transfers (CCT) ini terbukti cukup berhasil dalam menanggulangi kemiskinan yang dihadapi di negara-negara tersebut, terutama masalah kemiskinan kronis.</p>
							<p style="color:black">Sebagai sebuah program bantuan sosial bersyarat, PKH membuka akses keluarga miskin terutama ibu hamil dan anak untuk memanfaatkan berbagai fasilitas layanan kesehatan (faskes) dan fasilitas layanan pendidikan (fasdik) yang tersedia di sekitar mereka.Manfaat PKH juga mulai didorong untuk mencakup penyandang disabilitas dan lanjut usia dengan mempertahankan taraf kesejahteraan sosialnya sesuai dengan amanat konstitusi dan Nawacita Presiden RI.</p>
							<p style="color:black">Melalui PKH, KM didorong untuk memiliki akses dan memanfaatkan pelayanan sosial dasar kesehatan, pendidikan, pangan dan gizi,perawatan, dan pendampingan, termasuk akses terhadap berbagai program perlindungan sosial lainnya yang merupakan program komplementer secara berkelanjutan. PKH diarahkan untuk menjadi episentrum dan center of excellence penanggulangan kemiskinan yang mensinergikan berbagai program perlindungan dan pemberdayaan sosial nasional.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

</div><!-- /.panel-->
@endsection
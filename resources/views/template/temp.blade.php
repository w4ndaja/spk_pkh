<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SPK-PKH</title>
	<link href="{{url('assets/css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{url('assets/css/font-awesome.min.css')}}" rel="stylesheet">
	<link href="{{url('assets/css/datepicker3.css')}}" rel="stylesheet">
	<link href="{{url('assets/css/styles.css')}}" rel="stylesheet">
	<!-- Custom styles for this page -->
	<link href=" {{url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">

	<!--Custom Font-->
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
	<script src="js/respond.min.js"></script>
	<![endif]-->
	<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
	<nav class="navbar navbar-custom navbar-fixed-top" role="navigation">
		<div class="container-fluid">
			<div class="navbar-header" style="display:flex;">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse"><span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span></button>
				<a class="navbar-brand" href="#"><span>SPK_PKH</span>Admin</a>
				<div class="dropdown" style="margin-left: auto;margin-top:auto;margin-bottom:auto">
					<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" style="border-radius:50%">
						<i class="fa fa-user"></i>
					</button>
					<ul class="dropdown-menu" aria-labelledby="dropdownMenu1" style="margin-top:12px;right:0;left:auto">
						<li>
							<form action="{{route('logout')}}" method="post">
								@method('delete')
								@csrf
								<button type="submit" class="btn btn-default" style="background-color: white;border:none" href="#"><i class="fa fa-sign-out mr-1"></i>Logout</button>
							</form>
						</li>
					</ul>
				</div>
			</div>
		</div><!-- /.container-fluid -->
	</nav>
	<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
		<div class="profile-sidebar">
			<div class="profile-userpic">
				<img src="{{asset('img/user.svg')}}" class="img-responsive" alt="">
			</div>
			<div class="profile-usertitle">
				<div class="profile-usertitle-name">Admin</div>
				<div class="profile-usertitle-status"><span class="indicator label-success"></span>Online</div>
			</div>
			<div class="clear"></div>
		</div>
		<div class="divider"></div>
		{{-- <form role="search">
			<div class="form-group">
				<input type="text" class="form-control" placeholder="Search">
			</div>
		</form> --}}
		@yield('li')
	</div>
	<!--/.sidebar-->

	<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">

		@yield('header')

		<div class="row">
			<div class="col-lg-12">
				@yield('konten')

			</div><!-- /.col-->
			<div class="col-sm-12">
				<p class="back-link">PENDUKUNG KEPUTUSAN PENERIMA BANTUAN PKH DESA NDIWAR</p>
			</div>
		</div><!-- /.row -->
	</div>
	<!--/.main-->

	@yield('modal')

	<!-- jQuery -->
	<script src="{{url('plugins/jquery/jquery.min.js')}}"></script>
	<script src="{{ url('assets/js/bootstrap.min.js') }}"></script>
	<script src="{{ url('assets/js/chart.min.js') }}"></script>
	<script src="{{ url('assets/js/chart-data.js') }}"></script>
	<script src="{{ url('assets/js/easypiechart.js') }}"></script>
	<script src="{{ url('assets/js/easypiechart-data.js') }}"></script>
	<script src="{{ url('assets/js/bootstrap-datepicker.js') }}"></script>
	<script src="{{ url('assets/js/custom.js') }}"></script>
	<script src=" {{ url('plugins/datatables/jquery.dataTables.min.js')}}"></script>
	<script src=" {{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>

	<script type="text/javascript">
		$(function () {
            $("#example1").DataTable();
            $('#example2').DataTable({
              "paging": true,
              "lengthChange": false,
              "searching": false,
              "ordering": true,
              "info": true,
              "autoWidth": false,
            });
          });

	</script>

	@yield('js')

</body>

</html>
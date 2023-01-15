<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, intial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<style>
		table.static {
			position: relative;
			border: 1px solid #543535;
		}
	</style>
	<title>Cetak data Masyarakat</title>
</head>
<body>

	<div class="form-group">
		<p align="center"><b>Laporan data Masyarakat</b></p>
		<table class="static" align="center" rules="all" border="1px" style="width: 95%;">
			<thead>
          <tr>
            <th>No</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Alamat</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $key)
          <tr>
            <td> {{ $loop->iteration }} </td>
            <td> {{$key->nik}} </td>
            <td> {{$key->nama}} </td>
            <td> {{$key->alamat}}</td>
          </tr>
          @endforeach
        </tbody>
		</table>
	</div>

	<script type="text/javascript">
		window.print();
	</script>

</body>
</html>
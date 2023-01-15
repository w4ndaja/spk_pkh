<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kriteria</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Kriteria</th>
                <th>Nama Kriteria</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kriteria as $key => $item)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$item->kode_kri}}</td>
                <td>{{$item->nama_kri}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pkh Record</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NAMA_PENERIMA</th>
                <th>NIK_KTP</th>
                <th>TAHAP</th>
                <th>BANSOS</th>
                <th>IDSEMESTA</th>
                <th>NO_KEL</th>
                <th>NM_PROV</th>
                <th>NM_KAB</th>
                <th>NM_KEC</th>
                <th>NM_KEL /DESA</th>
                <th>Alamat</th>
                <th>Umur</th>
                <th>Tanggungan</th>
                <th>Status</th>
                <th>Penghasilan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pkhRecord as $key => $item)
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$item->masyarakat->nama}}</td>
                <td>{{$item->masyarakat->nik}}</td>
                <td>{{$item->tahap}}</td>
                <td>{{$item->bansos}}</td>
                <td>{{$item->idsemesta}}</td>
                <td>{{$item->no_kel}}</td>
                <td>{{$item->nm_prov}}</td>
                <td>{{$item->nm_kab}}</td>
                <td>{{$item->nm_kec}}</td>
                <td>{{$item->nm_kel}}</td>
                <td>{{$item->alamat}}</td>
                <td>{{$item->umur}}</td>
                <td>{{$item->tanggungan}}</td>
                <td>{{$item->status}}</td>
                <td>{{$item->penghasilan}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
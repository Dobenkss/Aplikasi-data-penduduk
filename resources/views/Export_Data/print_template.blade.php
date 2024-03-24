<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Penduduk</title>
</head>
<body>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">NIK</th>
                <th scope="col">Nama Lengkap</th>
                <th scope="col">Tanggal Lahir</th>
                <th scope="col">Jenis Kelamin</th>
                <th scope="col">Alamat</th>
                <th scope="col">Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penduduk as $pdd)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $pdd->nik }}</td>
                    <td>{{ $pdd->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($pdd->tgl_lahir)->format('d-m-Y') }}</td>
                    <td>{{ $pdd->jenis_kelamin }}</td>
                    <td>{{ $pdd->alamat }}, {{ $pdd->kabupaten->name }}, {{ $pdd->provinsi->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($pdd->updated_at)->format('d-m-Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

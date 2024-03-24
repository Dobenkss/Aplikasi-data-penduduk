@extends('Layout.main')
@section('content')
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1>EXPORT DATA</h1>
            <div class="d-flex align-items-center">
                <a href="#" class="btn btn-success mr-3" data-toggle="modal" data-target="#downloadModal">EXPORT</a>
            </div>
        </div>
    </section>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session('delete'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('delete') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>List Data Penduduk</h4>
            <form action="{{ url('admin-export') }}" method="GET" class="form-inline" id="filterForm">
                <div class="mr-2">
                    <select class="custom-select" id="provinsiFilter" name="provinsi" onchange="applyFilter()">
                        <option value="">Pilih Provinsi</option>
                        @foreach ($provinsi as $prov)
                            <option value="{{ $prov->id }}" {{ $provinsiSelected == $prov->id ? 'selected' : '' }}>
                                {{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mr-3">
                    <select class="custom-select" id="kabupatenFilter" name="kabupaten" onchange="applyFilter()">
                        <option value="">Pilih Kabupaten</option>
                        @foreach ($kabupaten as $kab)
                            <option value="{{ $kab->id }}" {{ $kabupatenId == $kab->id ? 'selected' : '' }}>
                                {{ $kab->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body col">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col" style="max-width: 100px;">NIK</th>
                            <th scope="col" style="max-width: 300px;">Nama Lengkap</th>
                            <th scope="col">Tanggal Lahir</th>
                            <th scope="col">Jenis Kelamin</th>
                            <th scope="col" style="max-width: 300px;">Alamat</th>
                            <th scope="col">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($penduduk as $pdd)
                            <tr>
                                <th scope="row">{{ $no++ }}</th>
                                <td style="padding: 10px;">{{ $pdd->nik }}</td>
                                <td style="padding: 10px;">{{ $pdd->name }}</td>
                                <td style="padding: 10px;">
                                    {{ \Carbon\Carbon::parse($pdd->tgl_lahir)->format('d-m-Y') }}
                                </td>
                                <td style="padding: 10px;">{{ $pdd->jenis_kelamin }}</td>
                                <td style="padding: 10px;">{{ $pdd->alamat }}, {{ $pdd->kabupaten }},
                                    {{ $pdd->provinsi }}</td>
                                <td style="padding: 10px;">
                                    {{ \Carbon\Carbon::parse($pdd->updated_at)->format('d-m-Y H:i:s') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade" id="downloadModal" tabindex="-1" role="dialog" aria-labelledby="downloadModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="downloadModalLabel">Pilih Tindakan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" <p>Pilih opsi berikut:</p>
                        <button type="button" class="btn btn-primary" onclick="exportExcel()">Unduh Excel</button>
                        <button type="button" class="btn btn-primary" onclick="printPage()">Print Out</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function exportExcel() {
                var provinsiFilter = document.getElementById('provinsiFilter').value;
                var kabupatenFilter = document.getElementById('kabupatenFilter').value;

                window.location.href = "{{ route('export.excel') }}" + "?provinsi=" + provinsiFilter + "&kabupaten=" +
                    kabupatenFilter;
            }

            function downloadExcel() {
                window.location.href = "{{ url('/admin-export-excel') }}";
            }


            function printPage() {
                var printWindow = window.open('', '_blank');
                printWindow.document.write('<html><head><title>Data Penduduk</title>');
                printWindow.document.write(
                    '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">'
                );
                printWindow.document.write('<style>');
                printWindow.document.write('.container { max-width: 100%; padding: 0 15px; margin: 0 auto; }');
                printWindow.document.write('.card { border: 1px solid rgba(0, 0, 0, 0.125); border-radius: 0.25rem; }');
                printWindow.document.write(
                    '.card-header { background-color: #007bff; color: #fff; padding: 0.75rem 1.25rem; }');
                printWindow.document.write('.table { width: 100%; margin-bottom: 1rem; color: #212529; }');
                printWindow.document.write(
                    '.table th, .table td { padding: 0.75rem; vertical-align: top; border-top: 1px solid #dee2e6; }');
                printWindow.document.write('.table thead th { vertical-align: bottom; border-bottom: 2px solid #dee2e6; }');
                printWindow.document.write('.thead-dark th { color: #fff; background-color: #343a40; border-color: #454d55; }');
                printWindow.document.write('</style>');
                printWindow.document.write('</head><body>');

                printWindow.document.write('<div class="container">');
                printWindow.document.write('<div class="card">');
                printWindow.document.write('<div class="card-header">List Data Penduduk</div>');
                printWindow.document.write('<div class="card-body">');
                printWindow.document.write('<div class="table-responsive">');
                printWindow.document.write('<table class="table table-bordered">');
                printWindow.document.write('<thead class="thead-dark">');
                printWindow.document.write('<tr>');
                printWindow.document.write('<th scope="col">No.</th>');
                printWindow.document.write('<th scope="col">NIK</th>');
                printWindow.document.write('<th scope="col">Nama Lengkap</th>');
                printWindow.document.write('<th scope="col">Tanggal Lahir</th>');
                printWindow.document.write('<th scope="col">Jenis Kelamin</th>');
                printWindow.document.write('<th scope="col">Alamat</th>');
                printWindow.document.write('<th scope="col">Timestamp</th>');
                printWindow.document.write('</tr>');
                printWindow.document.write('</thead>');
                printWindow.document.write('<tbody>');

                @foreach ($penduduk as $pdd)
                    printWindow.document.write('<tr>');
                    printWindow.document.write('<td>{{ $loop->iteration }}</td>');
                    printWindow.document.write('<td>{{ $pdd->nik }}</td>');
                    printWindow.document.write('<td>{{ $pdd->name }}</td>');
                    printWindow.document.write('<td>{{ \Carbon\Carbon::parse($pdd->tgl_lahir)->format('d-m-Y') }}</td>');
                    printWindow.document.write('<td>{{ $pdd->jenis_kelamin }}</td>');
                    printWindow.document.write('<td>{{ $pdd->alamat }}, {{ $pdd->kabupaten }}, {{ $pdd->provinsi }}</td>');
                    printWindow.document.write(
                        '<td>{{ \Carbon\Carbon::parse($pdd->updated_at)->format('d-m-Y H:i:s') }}</td>');
                    printWindow.document.write('</tr>');
                @endforeach

                printWindow.document.write('</tbody></table>');
                printWindow.document.write('</div></div></div>');
                printWindow.document.write('</div></body></html>');

                printWindow.document.addEventListener('DOMContentLoaded', function() {
                    printWindow.print();
                });

                printWindow.document.close();
            }
        </script>
        <script>
            function loadKabupaten() {
                var provinsiId = document.getElementById('provinsiFilter').value;
                var url = '/get-kabupaten/' + provinsiId;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        var kabupatenSelect = document.getElementById('kabupatenFilter');
                        kabupatenSelect.innerHTML = '<option value="">Pilih Kabupaten</option>';
                        data.forEach(kab => {
                            kabupatenSelect.innerHTML += '<option value="' + kab.id + '">' + kab.name + '</option>';
                        });
                    })
                    .catch(error => console.error('Error:', error));
            }

            document.getElementById('provinsiFilter').addEventListener('change', loadKabupaten);
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const urlParams = new URLSearchParams(window.location.search);
                if (urlParams.has('search')) {
                    urlParams.delete('search');
                    const newUrl = window.location.pathname + '?' + urlParams.toString();
                    window.history.replaceState({}, document.title, newUrl);
                }
            });
        </script>
        <script>
            window.onload = function() {
                if (performance.navigation.type === 1) {
                    var url = new URL(window.location.href);
                    url.search = '';
                    window.history.replaceState(null, null, url);
                }
            };
        </script>
    </div>
@endsection

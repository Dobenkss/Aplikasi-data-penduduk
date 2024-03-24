@extends('Layout.main')
@section('content')
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1>DATA PENDUDUK</h1>
            <div class="d-flex align-items-center">
                <a href="/admin-penduduk-create" class="btn btn-success mr-3">TAMBAH</a>
                <form action="{{ url('admin-penduduk') }}" method="GET" class="form-inline">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari NIK/Nama..." name="search"
                            value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </div>
                        @if (!request()->has('search'))
                            <script>
                                document.querySelector('.form-control[name="search"]').value = '';
                            </script>
                        @endif
                    </div>
                </form>
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
            <form action="{{ url('admin-penduduk') }}" method="GET" class="form-inline" id="filterForm">
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
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = ($penduduk->currentPage() - 1) * $penduduk->perPage() + 1;
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
                                <td style="padding: 10px;">
                                    <div class="d-flex flex-row">
                                        <div>
                                            <a href="/admin-penduduk-edit/{{ $pdd->id }}"
                                                class="btn btn-md bg-primary text-light btn-rounded mr-2">
                                                <i class="fa-solid fas fa-pen"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <form action="/admin-penduduk-destroy/{{ $pdd->id }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-md bg-danger text-light btn-rounded">
                                                    <i class="fa-solid fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer text-right">
                <nav class="d-inline-block">
                    <ul class="pagination mb-0">
                        @if ($penduduk->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $penduduk->previousPageUrl() }}"><i
                                        class="fas fa-chevron-left"></i></a>
                            </li>
                        @endif

                        @for ($i = 1; $i <= $penduduk->lastPage(); $i++)
                            <li class="page-item {{ $i == $penduduk->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $penduduk->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if ($penduduk->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $penduduk->nextPageUrl() }}"><i
                                        class="fas fa-chevron-right"></i></a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
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

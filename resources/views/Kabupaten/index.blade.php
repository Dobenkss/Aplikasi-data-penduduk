@extends('Layout.main')
@section('content')
    <section class="section">
        <div class="section-header d-flex justify-content-between align-items-center">
            <h1>DATA KABUPATEN</h1>
            <div class="d-flex align-items-center">
                <a href="/admin-kabupaten-create" class="btn btn-success mr-3">TAMBAH</a>
                <form action="{{ url('admin-kabupaten') }}" method="GET" class="form-inline">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari Kabupaten..." name="search"
                            value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Cari</button>
                        </div>
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
            <h4 class="mb-0">List Data Kabupaten</h4>
            <form action="{{ url('admin-kabupaten') }}" method="GET" class="form-inline" id="filterForm">
                <div class="mr-3">
                    <select class="custom-select" id="provinsiFilter" name="provinsi" onchange="saveFilterState()">
                        <option value="">Pilih Provinsi</option>
                        @foreach ($provinsi as $prov)
                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary" id="filterButton">Filter</button>
                </div>
            </form>
        </div>
        <div class="card-body col">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Nama Kabupaten</th>
                            <th scope="col">Nama Provinsi</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $no = 1;
                        @endphp
                        @foreach ($kabupaten as $kab)
                            <tr>
                                <th scope="row">{{ $no++ }}</th>
                                <td>{{ $kab->name }}</td>
                                <td>{{ $kab->provinsi->name }}</td>
                                <td>
                                    <div class="d-flex flex-row">
                                        <div>
                                            <a href="/admin-kabupaten-edit/{{ $kab->id }}"
                                                class="btn btn-md bg-primary text-light btn-rounded mr-2">
                                                <i class="fa-solid fas fa-pen"></i>
                                            </a>
                                        </div>
                                        <div>
                                            <form action="/admin-kabupaten-destroy/{{ $kab->id }}" method="POST">
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
                        @if ($kabupaten->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $kabupaten->previousPageUrl() }}"><i
                                        class="fas fa-chevron-left"></i></a>
                            </li>
                        @endif

                        @for ($i = 1; $i <= $kabupaten->lastPage(); $i++)
                            <li class="page-item {{ $i == $kabupaten->currentPage() ? 'active' : '' }}">
                                <a class="page-link" href="{{ $kabupaten->url($i) }}">{{ $i }}</a>
                            </li>
                        @endfor

                        @if ($kabupaten->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $kabupaten->nextPageUrl() }}"><i
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
    </div>
    <script>
        document.getElementById('filterButton').addEventListener('click', function() {
            document.getElementById('filterForm').submit();
        });

        window.onload = function() {
            if (performance.navigation.type === 1) {
                localStorage.removeItem('provinsiFilter');
                window.location.href = window.location.href.split('?')[0];
            }
        };
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
@endsection

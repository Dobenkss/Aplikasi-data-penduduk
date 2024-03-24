@extends('Layout.main')
@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('/admin-kabupaten') }}"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>DATA KABUPATEN</h1>
        </div>

        <div class="section-body">
        </div>
    </section>

    <div class="card">
        <div class="card-header">
            <h4>Input Data Kabupaten</h4>
        </div>
        <div class="card-body col">
            <form action="admin-kabupaten-store" method="post">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Kabupaten</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="provinsi_id">Provinsi</label>
                    <select class="form-control" name="provinsi_id" id="provinsi_id" required>
                        <option value="">Pilih Provinsi</option>
                        @foreach ($provinsi as $prov)
                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
    </div>
@endsection

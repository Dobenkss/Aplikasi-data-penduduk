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
            <h4>Edit Data Kabupaten</h4>
        </div>
        <div class="card-body col">
            <form action="/admin-kabupaten-update/{{ $edit->id }}" method="post">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Kabupaten</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $edit->name }}" required>
                </div>
                <div class="mb-3">
                    <label for="provinsi" class="form-label">Provinsi</label>
                    <select class="form-control" id="provinsi" name="provinsi_id" required>
                        @foreach ($provinsi as $prov)
                            <option value="{{ $prov->id }}" {{ $edit->provinsi_id == $prov->id ? 'selected' : '' }}>
                                {{ $prov->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
    </div>
@endsection

@extends('Layout.main')
@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('/admin-provinsi') }}"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>DATA PROVINSI</h1>
        </div>

        <div class="section-body">
        </div>
    </section>

    <div class="card">
        <div class="card-header">
            <h4>Edit Data Provinsi</h4>
        </div>
        <div class="card-body col">
            <form action="/admin-provinsi-update/{{ $edit->id }}" method="post">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Provinsi</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ $edit->name }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
    </div>
@endsection

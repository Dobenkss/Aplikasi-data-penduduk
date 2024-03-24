@extends('Layout.main')
@section('content')
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ url('/admin-penduduk') }}"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>DATA PENDUDUK</h1>
        </div>

        <div class="section-body">
        </div>
    </section>

    <div class="card">
        <div class="card-header">
            <h4>Input Data Penduduk</h4>
        </div>
        <div class="card-body col">
            <form action="{{ url('admin-penduduk-store') }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="nik" class="form-label">NIK</label>
                    <input type="number" class="form-control" id="nik" name="nik" required maxlength="18"
                        autocomplete="off">
                    @error('nik')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control" id="name" name="name" required maxlength="255"
                        autocomplete="off">
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" class="form-control" id="tgl_lahir" name="tgl_lahir" required>
                    @error('tgl_lahir')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label><br>
                    <input type="radio" id="laki_laki" name="jenis_kelamin" value="Laki-laki" required>
                    <label for="laki_laki">Laki-laki</label><br>
                    <input type="radio" id="perempuan" name="jenis_kelamin" value="Perempuan" required>
                    <label for="perempuan">Perempuan</label><br>
                    @error('jenis_kelamin')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" required autocomplete="off">
                    @error('alamat')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="provinsi" class="form-label">Provinsi</label>
                    <select class="form-control" id="provinsi" name="provinsi" required>
                        <option value="">Pilih Provinsi</option>
                        @foreach ($provinsi as $prov)
                            <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                        @endforeach
                    </select>
                    @error('provinsi')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="kabupaten" class="form-label">Kabupaten</label>
                    <select class="form-control" id="kabupaten" name="kabupaten" required>
                        <option value="">Pilih Kabupaten</option>
                    </select>
                    @error('kabupaten')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#provinsi').change(function() {
                    var provinsiId = $(this).val();
                    if (provinsiId) {
                        $.ajax({
                            type: "GET",
                            url: "/get-kabupaten/" + provinsiId,
                            success: function(res) {
                                if (res) {
                                    $("#kabupaten").empty();
                                    $("#kabupaten").append(
                                        '<option value="">Pilih Kabupaten</option>');
                                    $.each(res, function(key, value) {
                                        $("#kabupaten").append('<option value="' + value
                                            .id + '">' + value.name + '</option>');
                                    });
                                } else {
                                    $("#kabupaten").empty();
                                }
                            }
                        });
                    } else {
                        $("#kabupaten").empty();
                    }
                });
            });
        </script>
    </div>
@endsection

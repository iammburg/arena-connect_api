@extends('dashboard-layouts.app')
@section('title', 'Tambah Lapangan Olahraga')
@section('content')
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('fields.index') }}">Tambah Lapangan Olahraga</a>
                </li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="form-validation">
                            <form class="form-valide" action="{{ route('fields.store') }}" method="post">
                                @csrf
                                @method('POST')
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="name">Nama Lapangan <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" value="{{ old('name') }}" required
                                            id="name" name="name" placeholder="Masukkan Nama Lapangan">
                                        @error('name')
                                            <div class="invalid-feedback animated fadeInDown" style="display: block;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="field_centre_id">Dari GOR<span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <select class="form-control" id="field_centre_id" name="field_centre_id">
                                            <option value="" disabled selected>Pilih Pusat Lapangan</option>
                                            @foreach ($field_centres as $field_centre)
                                                <option value="{{ $field_centre->id }}"
                                                    {{ old('field_centre_id') == $field_centre->id ? 'selected' : '' }}>
                                                    {{ $field_centre->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('field_centre_id')
                                            <div class="invalid-feedback animated fadeInDown" style="display: block;">
                                                {{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="type">Tipe Lapangan<span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <select class="form-control" value="{{ old('type') }}" required id="type"
                                            name="type">
                                            <option value="" disabled selected>Pilih Tipe Lapangan</option>
                                            <option value="Futsal" {{ old('type') == 'Futsal' ? 'selected' : '' }}>
                                                Futsal</option>
                                            <option value="Badminton" {{ old('type') == 'Badminton' ? 'selected' : '' }}>
                                                Badminton</option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback animated fadeInDown" style="display: block;">
                                                {{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="descriptions">Deskripsi<span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <textarea class="form-control" id="descriptions" name="descriptions" rows="5"
                                            placeholder="Masukkan deskripsi lapangan">{{ old('descriptions') }}</textarea>
                                        @error('descriptions')
                                            <div class="invalid-feedback animated fadeInDown" style="display: block;">
                                                {{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <input type="hidden" name="status" value="Tersedia">
                                <div class="form-group row">
                                    <div class="col-lg-8 ml-auto">
                                        <button type="submit" class="btn btn-primary">Tambah</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #/ container -->
@endsection

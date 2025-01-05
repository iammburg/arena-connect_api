@extends('dashboard-layouts.app')
@section('title', 'Tambah Lapangan Olahraga')
@section('content')
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('field-price-schedules.index') }}">Tambah Lapangan
                        Olahraga</a>
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
                            <form class="form-valide" action="{{ route('field-price-schedules.store') }}" method="post">
                                @csrf
                                @method('POST')
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="field_id">Jadwal Untuk Lapangan<span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <select class="form-control" id="field_id" name="field_id">
                                            <option value="" disabled selected>Pilih Lapangan</option>
                                            @foreach ($fields as $field)
                                                <option value="{{ $field->id }}"
                                                    {{ old('field_id') == $field->id ? 'selected' : '' }}>
                                                    {{ $field->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('field_id')
                                            <div class="invalid-feedback animated fadeInDown" style="display: block;">
                                                {{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="date">Tanggal Jadwal Berlaku <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="date" class="form-control" value="{{ old('date') }}" required
                                            id="date" name="date" placeholder="Masukkan Tanggal Jadwal Berlaku">
                                        @error('date')
                                            <div class="invalid-feedback animated fadeInDown" style="display: block;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="start_time">Jam Mulai <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="time" class="form-control" value="{{ old('start_time') }}" required
                                            id="start_time" name="start_time" placeholder="Masukkan Jam Mulai">
                                        @error('start_time')
                                            <div class="invalid-feedback animated fadeInDown" style="display: block;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="end_time">Jam Selesai <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="time" class="form-control" value="{{ old('end_time') }}" required
                                            id="end_time" name="end_time" placeholder="Masukkan Jam Selesai">
                                        @error('end_time')
                                            <div class="invalid-feedback animated fadeInDown" style="display: block;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <input type="hidden" name="is_booked" value="0">
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="price_from">Harga Normal <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="numeric" class="form-control" value="{{ old('price_from') }}" required
                                            id="price_from" name="price_from" placeholder="Masukkan Harga Normal">
                                        @error('price_from')
                                            <div class="invalid-feedback animated fadeInDown" style="display: block;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="price_to">Harga Tertinggi <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="numeric" class="form-control" value="{{ old('price_to') }}" required
                                            id="price_to" name="price_to" placeholder="Masukkan Harga Tertinggi">
                                        @error('price_to')
                                            <div class="invalid-feedback animated fadeInDown" style="display: block;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
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

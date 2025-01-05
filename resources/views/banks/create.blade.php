@extends('dashboard-layouts.app')
@section('title', 'Tambah Bank & Metode Pembayaran')
@section('content')
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('banks.index') }}">Tambah Bank & Metode Pembayaran</a>
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
                            <form class="form-valide" action="{{ route('banks.store') }}" method="post">
                                @csrf
                                @method('POST')
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="user_id">Atas Nama (Pemilik)<span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        @if (Auth::user()->role == 'Admin Aplikasi')
                                            <select class="form-control" id="user_id" name="user_id">
                                                <option value="" disabled selected>Pilih Pengguna Pemilik</option>
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}"
                                                        {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" class="form-control" value="{{ Auth::user()->name }}"
                                                readonly>
                                            <input type="hidden" id="user_id" name="user_id"
                                                value="{{ Auth::user()->id }}">
                                        @endif
                                        @error('user_id')
                                            <div class="invalid-feedback animated fadeInDown" style="display: block;">
                                                {{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="bank_name">Nama Bank <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" value="{{ old('bank_name') }}" required
                                            id="bank_name" name="bank_name" placeholder="Masukkan Nama Bank">
                                        @error('bank_name')
                                            <div class="invalid-feedback animated fadeInDown" style="display: block;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="account_number">Nomor Rekening <span
                                            class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="text" class="form-control" value="{{ old('account_number') }}"
                                            required id="account_number" name="account_number"
                                            placeholder="Masukkan Nomor Rekening">
                                        @error('account_number')
                                            <div class="invalid-feedback animated fadeInDown" style="display: block;">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="field_centre_id">Untuk GOR<span
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

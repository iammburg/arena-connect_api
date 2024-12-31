@extends('dashboard-layouts.app')
@section('title', 'Bank & Metode Pembayaran')
@section('content')
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('banks.index') }}">Table Bank & Metode Pembayaran</a>
                </li>
            </ol>
        </div>
    </div>
    <!-- row -->

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Table Bank & Metode Pembayaran</h4>
                        <a href="{{ route('banks.create') }}">
                            <button type="button" class="btn mb-1 btn-primary">Tambah Bank & Metode Pembayaran</button>
                        </a>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Pemilik</th>
                                        <th>Untuk GOR</th>
                                        <th>Nama Bank</th>
                                        <th>Nomor Rekening</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($banks->count() > 0)
                                        @foreach ($banks as $bank)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $bank->user->name }}</td>
                                                <td>{{ $bank->fieldCentre->name }}</td>
                                                <td>{{ $bank->bank_name }}</td>
                                                <td>{{ $bank->account_number }}</td>
                                                <td>
                                                    <a href="{{ route('banks.edit', $bank->id) }}"
                                                        class="btn btn-warning btn-sm" data-toggle="tooltip"><i
                                                            class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                                    {{-- Hapus Data --}}
                                                    <a href="#" class="btn btn-danger btn-sm" data-toggle="tooltip"
                                                        data-confirm="Yakin?|Apakah Anda yakin akan menghapus:  <b>{{ $bank->name }}</b>?"
                                                        data-confirm-yes="event.preventDefault();
                    document.getElementById('delete-portofolio-{{ $bank->id }}').submit();"><i
                                                            class="fas fa-trash" aria-hidden="true"></i></a>
                                                    <form id="delete-portofolio-{{ $bank->id }}"
                                                        action="{{ route('banks.destroy', $bank->id) }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- #/ container -->
@endsection

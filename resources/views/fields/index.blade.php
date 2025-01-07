@extends('dashboard-layouts.app')
@section('title', 'Lapangan Olahraga')
@section('content')
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('fields.index') }}">Table Lapangan Olahraga</a>
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
                        <h4 class="card-title">Table Lapangan Olahraga</h4>
                        <a href="{{ route('fields.create') }}">
                            <button type="button" class="btn mb-1 btn-primary">Tambah Lapangan</button>
                        </a>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Lapangan</th>
                                        <th>GOR</th>
                                        <th>Tipe</th>
                                        <th>Deskripsi</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($fields->count() > 0)
                                        @foreach ($fields as $field)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $field->name }}</td>
                                                <td>{{ $field->fieldCentre->name }}</td>
                                                <td>{{ $field->type }}</td>
                                                <td>{{ $field->descriptions }}</td>
                                                <td>{{ $field->status }}</td>
                                                <td>
                                                    <span data-toggle="tooltip" data-placement="top" title="Ubah Status">
                                                        <button type="button" class="btn btn-success btn-sm mb-1"
                                                            data-toggle="modal"
                                                            data-target="#updateStatus{{ $field->id }}">
                                                            <i class="fas fa-check" aria-hidden="true"></i>
                                                        </button>
                                                    </span>

                                                    <a href="{{ route('fields.edit', $field->id) }}"
                                                        class="btn btn-warning btn-sm mb-1" data-toggle="tooltip"><i
                                                            class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                                    {{-- Hapus Data --}}
                                                    <a href="#" class="btn btn-danger btn-sm mb-1"><i
                                                            class="fas fa-trash" aria-hidden="true"></i></a>
                                                    <form id="delete-portofolio-{{ $field->id }}"
                                                        action="{{ route('fields.destroy', $field->id) }}" method="POST"
                                                        style="display: none;">
                                                        @csrf
                                                        @method('delete')
                                                    </form>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="updateStatus{{ $field->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="updateStatusLabel{{ $field->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="updateStatusLabel{{ $field->id }}">
                                                                Ubah Status Lapangan</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('fields.update-status', $field->id) }}"
                                                            method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="modal-body">
                                                                <select name="status" id="status" class="form-control">
                                                                    <option value="Tersedia"
                                                                        {{ $field->status == 'Tersedia' ? 'selected' : '' }}>
                                                                        Tersedia
                                                                    </option>
                                                                    <option value="Telah dibooking"
                                                                        {{ $field->status == 'Telah dibooking' ? 'selected' : '' }}>
                                                                        Telah dibooking
                                                                    </option>
                                                                    <option value="Dalam perbaikan"
                                                                        {{ $field->status == 'Dalam perbaikan' ? 'selected' : '' }}>
                                                                        Dalam perbaikan
                                                                    </option>
                                                                    <option value="Tidak tersedia"
                                                                        {{ $field->status == 'Tidak tersedia' ? 'selected' : '' }}>
                                                                        Tidak tersedia
                                                                    </option>
                                                                </select>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Batal</button>
                                                                <button type="submit"
                                                                    class="btn btn-success">Konfirmasi</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
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

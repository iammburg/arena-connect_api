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
                                                    <a href="{{ route('fields.edit', $field->id) }}"
                                                        class="btn btn-warning btn-sm" data-toggle="tooltip"><i
                                                            class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                                    {{-- Hapus Data --}}
                                                    <a href="#" class="btn btn-danger btn-sm" data-toggle="tooltip"
                                                        data-confirm="Yakin?|Apakah Anda yakin akan menghapus:  <b>{{ $field->name }}</b>?"
                                                        data-confirm-yes="event.preventDefault();
                    document.getElementById('delete-portofolio-{{ $field->id }}').submit();"><i
                                                            class="fas fa-trash" aria-hidden="true"></i></a>
                                                    <form id="delete-portofolio-{{ $field->id }}"
                                                        action="{{ route('fields.destroy', $field->id) }}" method="POST"
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

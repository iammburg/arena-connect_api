@extends('dashboard-layouts.app')
@section('title', 'Manajemen Fasilitas')
@section('content')
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('facilities.index') }}">Table Daftar Fasilitas</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Table Daftar Fasilitas</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Fasilitas</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($facilities->count() > 0)
                                        @foreach ($facilities as $facility)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $facility->name }}</td>
                                                <td>
                                                    <a href="{{ route('facilities.edit', $facility->id) }}"
                                                        class="btn btn-warning btn-sm" data-toggle="tooltip"><i
                                                            class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                                    {{-- Hapus Data --}}
                                                    <a href="#" class="btn btn-danger btn-sm" data-toggle="tooltip"
                                                        data-confirm="Yakin?|Apakah Anda yakin akan menghapus:  <b>{{ $facility->name }}</b>?"
                                                        data-confirm-yes="event.preventDefault();
                    document.getElementById('delete-portofolio-{{ $facility->id }}').submit();"><i
                                                            class="fas fa-trash" aria-hidden="true"></i></a>
                                                    <form id="delete-portofolio-{{ $facility->id }}"
                                                        action="{{ route('facilities.destroy', $facility->id) }}"
                                                        method="POST" style="display: none;">
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

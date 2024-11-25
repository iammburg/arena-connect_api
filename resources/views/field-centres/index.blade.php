@extends('dashboard-layouts.app')
@section('title', 'Field Centres')
@section('content')
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">Home</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Table Pusat Olahraga</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Pusat Lapangan</th>
                                        <th>Pemilik</th>
                                        <th>Alamat</th>
                                        <th>Nomor Telepon</th>
                                        <th>Rating</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($field_centres as $field_centre)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $field_centre->name }}</td>
                                            <td>{{ $field_centre->user->name }}</td>
                                            <td>{{ $field_centre->address }}</td>
                                            <td>{{ $field_centre->phone_number }}</td>
                                            <td>{{ $field_centre->rating }}</td>
                                            <td>
                                                <a href="{{ route('field-centres.edit', $field_centre->id) }}"
                                                    class="btn btn-warning btn-sm" data-toggle="tooltip"><i
                                                        class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                                {{-- Hapus Data --}}
                                                <a href="#" class="btn btn-danger btn-sm" data-toggle="tooltip"
                                                    data-confirm="Yakin?|Apakah Anda yakin akan menghapus:  <b>{{ $field_centre->name }}</b>?"
                                                    data-confirm-yes="event.preventDefault();
                    document.getElementById('delete-portofolio-{{ $field_centre->id }}').submit();"><i
                                                        class="fas fa-trash" aria-hidden="true"></i></a>
                                                <form id="delete-portofolio-{{ $field_centre->id }}"
                                                    action="{{ route('field-centres.destroy', $field_centre->id) }}"
                                                    method="POST" style="display: none;">
                                                    @csrf
                                                    @method('delete')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
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

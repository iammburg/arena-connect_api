@extends('dashboard-layouts.app')
@section('title', 'Manajemen Pengguna')
@section('content')
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('users.index') }}">Table Daftar Pengguna</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Table Daftar Pengguna</h4>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Nomor Telepon</th>
                                        <th>Role</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($users->count() > 0)
                                        @foreach ($users as $user)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->phone_number }}</td>
                                                <td>{{ $user->role }}</td>
                                                <td>
                                                    <a href="{{ route('users.edit', $user->id) }}"
                                                        class="btn btn-warning btn-sm" data-toggle="tooltip"><i
                                                            class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                                    {{-- Hapus Data --}}
                                                    <a href="#" class="btn btn-danger btn-sm" data-toggle="tooltip"
                                                        data-confirm="Yakin?|Apakah Anda yakin akan menghapus:  <b>{{ $user->name }}</b>?"
                                                        data-confirm-yes="event.preventDefault();
                    document.getElementById('delete-portofolio-{{ $user->id }}').submit();"><i
                                                            class="fas fa-trash" aria-hidden="true"></i></a>
                                                    <form id="delete-portofolio-{{ $user->id }}"
                                                        action="{{ route('users.destroy', $user->id) }}" method="POST"
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

@extends('dashboard-layouts.app')
@section('title', 'Jadwal & Harga Olahraga')
@section('content')
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('field-price-schedules.index') }}">Table Jadwal & Harga
                        Olahraga</a>
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
                        <h4 class="card-title">Table Jadwal & Harga Olahraga</h4>
                        <a href="{{ route('field-price-schedules.create') }}">
                            <button type="button" class="btn mb-1 btn-primary">Tambah Jadwal & Harga</button>
                        </a>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Lapangan</th>
                                        <th>Tanggal</th>
                                        <th>Jam Awal</th>
                                        <th>Jam Akhir</th>
                                        <th>Status</th>
                                        <th>Harga Normal</th>
                                        <th>Harga Tertinggi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($fields->count() > 0)
                                        @foreach ($fields as $field)
                                            @foreach ($field->schedules as $schedule)
                                                <tr>
                                                    <td>{{ $loop->parent->iteration }}</td>
                                                    <td>{{ $field->name }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('d F Y') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->start_time)->format('H:i') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $schedule->end_time)->format('H:i') }}
                                                    </td>
                                                    <td>{{ $schedule->is_booked ? 'Telah Dibooking' : 'Tersedia' }}</td>
                                                    <td>Rp{{ number_format($field->prices->first()->price_from, 0, ',', '.') ?? 'N/A' }}
                                                    </td>
                                                    <td>Rp{{ number_format($field->prices->first()->price_to, 0, ',', '.') ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('field-price-schedules.edit', $field->id) }}"
                                                            class="btn btn-warning btn-sm" data-toggle="tooltip"><i
                                                                class="fas fa-pencil-alt" aria-hidden="true"></i></a>
                                                        {{-- Hapus Data --}}
                                                        <a href="#" class="btn btn-danger btn-sm"
                                                            data-toggle="tooltip"><i class="fas fa-trash-alt"
                                                                aria-hidden="true"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
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

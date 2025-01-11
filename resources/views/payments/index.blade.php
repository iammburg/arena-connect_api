@extends('dashboard-layouts.app')
@section('title', 'Konfirmasi Pembayaran')
@section('content')
    <div class="row page-titles mx-0">
        <div class="col p-md-0">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('payments.index') }}">Table Konfirmasi Pembayaran</a>
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
                        <h4 class="card-title">Table Konfirmasi Pembayaran</h4>
                        {{-- <a href="{{ route('payments.create') }}">
                            <button type="button" class="btn mb-1 btn-primary">Tambah Konfirmasi Pembayaran</button>
                        </a> --}}
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Atas Nama</th>
                                        <th>GOR</th>
                                        <th>Nama Lapangan</th>
                                        <th>Status</th>
                                        <th>Harga Total</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Order ID</th>
                                        <th>Bukti Pembayaran</th>
                                        @if (Auth::user()->role == 'Admin Lapangan' || Auth::user()->role == 'Admin Aplikasi')
                                            <th>Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($payments->count() > 0)
                                        @foreach ($payments as $payment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                @if ($payment->user)
                                                    <td>{{ $payment->user->name }}</td>
                                                @else
                                                    <td>-</td>
                                                @endif
                                                @if ($payment->booking->field->fieldCentre)
                                                    <td>{{ $payment->booking->field->fieldCentre->name }}</td>
                                                @else
                                                    <td>-</td>
                                                @endif
                                                @if ($payment->booking->field)
                                                    <td>{{ $payment->booking->field->name }}</td>
                                                @else
                                                    <td>-</td>
                                                @endif
                                                <td><span
                                                        class="badge {{ $payment->status == 'Belum'
                                                            ? 'badge-light'
                                                            : ($payment->status == 'Proses'
                                                                ? 'badge-info'
                                                                : ($payment->status == 'Selesai'
                                                                    ? 'badge-success'
                                                                    : ($payment->status == 'Ditolak'
                                                                        ? 'badge-danger'
                                                                        : ''))) }} px-2">{{ $payment->status }}</span>
                                                </td>
                                                <td>Rp{{ number_format($payment->total_payment, 0, ',', '.') }}</td>
                                                @if ($payment->bank)
                                                    <td>{{ $payment->bank->bank_name }} -
                                                        {{ $payment->bank->account_number }}</td>
                                                @else
                                                    <td>-</td>
                                                @endif
                                                <td>{{ $payment->order_id }}</td>
                                                <td>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                                        data-target="#paymentModal{{ $payment->id }}">
                                                        Tampilkan
                                                    </button>
                                                </td>
                                                <td>
                                                    @if (Auth::user()->role == 'Admin Lapangan' || Auth::user()->role == 'Admin Aplikasi')
                                                        @if ($payment->status == 'Proses')
                                                            <span data-toggle="tooltip" data-placement="top"
                                                                title="Konfirmasi Pembayaran">
                                                                <button type="button" class="btn btn-success btn-sm mb-1"
                                                                    data-toggle="modal"
                                                                    data-target="#approveModal{{ $payment->id }}">
                                                                    <i class="fas fa-check" aria-hidden="true"></i>
                                                                </button>
                                                            </span>

                                                            <span data-toggle="tooltip" data-placement="top"
                                                                title="Tolak Pembayaran">
                                                                <button type="button" class="btn btn-danger btn-sm mb-1"
                                                                    data-toggle="modal"
                                                                    data-target="#rejectModal{{ $payment->id }}">
                                                                    <i class="fas fa-times" aria-hidden="true"></i>
                                                                </button>
                                                            </span>
                                                        @else
                                                            <button class="btn btn-success btn-sm mb-1" disabled>
                                                                <i class="fas fa-check" aria-hidden="true"></i>
                                                            </button>
                                                            <button class="btn btn-danger btn-sm mb-1" disabled>
                                                                <i class="fas fa-times" aria-hidden="true"></i>
                                                            </button>
                                                        @endif
                                                    @endif
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="paymentModal{{ $payment->id }}">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Bukti Booking</h5>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            @if ($payment->receipt)
                                                                <img src="{{ asset('storage/receipts/' . $payment->receipt) }}"
                                                                    alt="Bukti Pembayaran" class="img-fluid"
                                                                    style="max-width: 100%; height: auto;">
                                                            @else
                                                                <p>Tidak ada bukti pembayaran</p>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="approveModal{{ $payment->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="approveModalLabel{{ $payment->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="approveModalLabel{{ $payment->id }}">
                                                                Konfirmasi Pembayaran</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin mengkonfirmasi pembayaran
                                                            ini?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Batal</button>
                                                            <form action="{{ route('payments.approve', $payment->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit"
                                                                    class="btn btn-success">Konfirmasi</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="rejectModal{{ $payment->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="rejectModalLabel{{ $payment->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="rejectModalLabel{{ $payment->id }}">Tolak
                                                                Pembayaran</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Apakah Anda yakin ingin menolak pembayaran ini?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Batal</button>
                                                            <form action="{{ route('payments.reject', $payment->id) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit"
                                                                    class="btn btn-danger">Tolak</button>
                                                            </form>
                                                        </div>
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

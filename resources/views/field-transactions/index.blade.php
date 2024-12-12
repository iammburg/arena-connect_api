@extends('dashboard-layouts.app')
@section('title', 'Daftar Transaksi')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Daftar Transaksi</h4>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th>Nama User</th>
                            <th>Nama GOR</th>
                            <th>Nama Lapangan</th>
                            <th>Metode Pembayaran</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Status</th>
                            <th style="width: 15%;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $transaction->user->name }}</td>
                                <td>{{ $transaction->fieldCentre->name }}</td>
                                <td>{{ $transaction->field->name }}</td>
                                <td>{{ $transaction->payment_method }}</td>
                                <td>{{ $transaction->date }}</td>
                                <td>{{ $transaction->booking_start }} - {{ $transaction->booking_end }}</td>
                                <td>{{ $transaction->status }}</td>
                                <td>
                                    <button class="btn btn-success btn-sm" onclick="showModal('Selesai', {{ $transaction->id }})">&#10003;</button>
                                    <button class="btn btn-danger btn-sm" onclick="showModal('Batal', {{ $transaction->id }})">&#10007;</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="statusModalLabel">Konfirmasi Perubahan Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin mengubah status transaksi ini menjadi <span id="modalStatus"></span>?</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="confirmAction">Konfirmasi</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
    function showModal(status, transactionId) {
        // Set status di modal
        document.getElementById('modalStatus').innerText = status;

        // Menyimpan transactionId di tombol konfirmasi
        document.getElementById('confirmAction').onclick = function() {
            // Lakukan aksi update status ke database di sini
            // Misalnya menggunakan AJAX atau mengarahkan ke route tertentu untuk memperbarui status
            
            // Contoh mengarahkan ke route update status (ganti dengan route yang sesuai)
            window.location.href = '/transactions/update-status/' + transactionId + '?status=' + status;
        };

        // Menampilkan modal
        $('#statusModal').modal('show');
    }
</script>
@endsection

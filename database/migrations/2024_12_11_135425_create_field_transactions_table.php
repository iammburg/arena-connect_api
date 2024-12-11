<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('field-transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // Relasi dengan tabel users
            $table->foreignId('field_centre_id')->constrained('field_centres')->onDelete('cascade');  // Relasi dengan tabel field_centres
            $table->foreignId('field_id')->constrained('fields')->onDelete('cascade');  // Relasi dengan tabel fields
            $table->string('payment_method');  // Metode pembayaran
            $table->string('status');  // Status transaksi
            $table->date('date');  // Tanggal transaksi
            $table->time('booking_start');  // Waktu mulai booking
            $table->time('booking_end');  // Waktu selesai booking
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('field-transactions');
    }
}

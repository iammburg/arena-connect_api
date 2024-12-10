<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_method', ['BRI - 223134127532643','BNI - 3412312385'])->change();
            $table->enum('status', ['Belum', 'Proses', 'Selesai', 'Ditolak'])->default('Belum')->change();
            $table->dropColumn('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_method', ['BRI','BNI']);
            $table->enum('status', ['belum', 'proses', 'selesai'])->default('belum');
            $table->date('date');
        });
    }
};

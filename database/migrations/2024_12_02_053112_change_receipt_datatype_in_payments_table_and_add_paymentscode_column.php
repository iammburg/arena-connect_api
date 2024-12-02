<?php

use App\Models\Payments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Ubah tipe data kolom receipt menjadi JSON
            $table->json('receipt')->change();

            // Tambahkan kolom payment_code setelah kolom order_id
            $table->char('payment_code')->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            // Ubah kembali tipe data kolom receipt ke tipe sebelumnya
            $table->char('receipt', length: 45)->change();

            // Hapus kolom payment_code
            $table->dropColumn('payment_code');
        });
    }
};

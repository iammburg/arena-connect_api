<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_method', ['BRI - 223134127532643', 'BNI - 3412312385', 'Belum Memilih'])
                ->default('Belum Memilih')
                ->change();
            $table->char('receipt', 45)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('payment_method', ['BRI - 223134127532643', 'BNI - 3412312385'])
                ->default(null)
                ->change();
            $table->char('receipt', 45)->nullable(false)->change();
        });
    }
};

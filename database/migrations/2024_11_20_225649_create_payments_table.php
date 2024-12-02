<?php

use App\Models\Payments;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnUpdate();
            $table->foreignId('booking_id')
                ->nullable()
                ->constrained('bookings')
                ->cascadeOnUpdate();
            // $table->foreignId('sparring_id')
            //     ->nullable()
            //     ->constrained('sparring')
            //     ->cascadeOnUpdate();
            $table->decimal('total_payment');
            $table->enum('payment_method', ['BRI','BNI']);
            $table->enum('status', ['belum', 'proses', 'selesai']);
            $table->char('order_id', length: 45);
            $table->char('receipt', length: 45);
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

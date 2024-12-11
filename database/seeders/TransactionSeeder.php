<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        Transaction::create([
            'user_id' => 1,
            'field_centre_id' => 1,
            'field_id' => 1, // Pastikan ini adalah ID lapangan yang valid
            'payment_method' => 'Transfer Bank',
            'status' => 'Pending',
            'date' => '2024-12-11',
            'booking_start' => '2024-12-11 09:00:00', // Tambahkan waktu mulai
            'booking_end' => '2024-12-11 10:00:00', // Tambahkan waktu selesai
        ]);

        Transaction::create([
            'user_id' => 2,
            'field_centre_id' => 1,
            'field_id' => 2,
            'payment_method' => 'Cash',
            'status' => 'Selesai',
            'date' => '2024-12-11',
            'booking_start' => '2024-12-11 12:00:00', // Tambahkan waktu mulai
            'booking_end' => '2024-12-11 13:00:00', // Tambahkan waktu selesai
        ]);
    }
}

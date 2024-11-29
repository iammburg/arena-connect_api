<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            [
                'name' => 'Toilet',
            ],
            [
                'name' => 'WiFi',
            ],
            [
                'name' => 'Cafe & Resto',
            ],
            [
                'name' => 'Musholla',
            ],
            [
                'name' => 'Parkir Mobil',
            ],
            [
                'name' => 'Parkir Motor',
            ],
            [
                'name' => 'Ruang Ganti',
            ],
            [
                'name' => 'Tribun',
            ],
            [
                'name' => 'Jual Makanan',
            ],
            [
                'name' => 'Jual Minuman',
            ],
        ];

        DB::table('facilities')->insert($facilities);
    }
}

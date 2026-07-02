<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            [
                'name' => 'Meja Jendela untuk Dua',
                'type' => 'standard',
                'capacity' => 2,
                'deposit' => 0,
                'description' => 'Meja dua kursi dengan pemandangan jalan, cocok untuk ngobrol santai.',
            ],
            [
                'name' => 'Meja Taman',
                'type' => 'outdoor',
                'capacity' => 4,
                'deposit' => 0,
                'description' => 'Duduk di area luar yang teduh, dikelilingi tanaman.',
            ],
            [
                'name' => 'Kursi Bar Barista',
                'type' => 'komunal',
                'capacity' => 6,
                'deposit' => 0,
                'description' => 'Kursi baris depan menghadap mesin espresso.',
            ],
            [
                'name' => 'Ruang Roastery',
                'type' => 'privat',
                'capacity' => 8,
                'deposit' => 100000,
                'description' => 'Ruang semi-tertutup di samping alat roasting, cocok untuk pertemuan kecil.',
            ],
            [
                'name' => 'Ruang Cupping',
                'type' => 'privat',
                'capacity' => 12,
                'deposit' => 150000,
                'description' => 'Ruang privat terbesar kami, biasa dipakai untuk cupping session dan acara privat.',
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}

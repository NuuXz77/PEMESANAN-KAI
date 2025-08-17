<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\rute;

class RuteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rutes = [
            [1, 2, 150, '03:00'],
            [2, 3, 350, '06:00'],
            [3, 4, 300, '05:30'],
            [4, 5, 250, '04:30'],
            [5, 6, 200, '03:45'],
            [6, 7, 180, '03:15'],
            [7, 8, 120, '02:30'],
            [8, 9, 100, '02:00'],
            [9, 10, 90, '01:45'],
            [10, 11, 110, '02:15'],
            [11, 12, 130, '02:45'],
            [12, 13, 140, '03:00'],
            [13, 14, 160, '03:30'],
            [14, 15, 170, '03:45'],
            [15, 16, 180, '04:00'],
            [16, 17, 190, '04:15'],
            [17, 18, 200, '04:30'],
            [18, 19, 210, '04:45'],
            [19, 20, 220, '05:00'],
            [20, 1, 230, '05:15'],
        ];

        foreach ($rutes as $i => $rt) {
            rute::create([
                'Kode_Rute' => 'RT' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'asal_id' => $rt[0],
                'tujuan_id' => $rt[1],
                'jarak_tempuh' => $rt[2],
                'durasi' => $rt[3],
                'jalur_rute' => null,
            ]);
        }
    }
}

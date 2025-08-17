<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\jadwal;

class JadwalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $date = now()->format('Ymd');
        for ($i = 1; $i <= 20; $i++) {
            $depart = now()->addDays($i)->setTime(rand(5, 20), rand(0, 59));
            $arrive = (clone $depart)->addHours(rand(2, 6))->addMinutes(rand(0, 59));
            jadwal::create([
                'Kode_Jadwal' => 'JDWL' . $date . 'KRT' . str_pad($i, 3, '0', STR_PAD_LEFT) . 'RT' . str_pad($i, 3, '0', STR_PAD_LEFT) . str_pad($i, 4, '0', STR_PAD_LEFT),
                'id_kereta' => $i,
                'id_rute' => $i,
                'waktu_keberangkatan' => $depart,
                'waktu_kedatangan' => $arrive,
            ]);
        }
    }
}

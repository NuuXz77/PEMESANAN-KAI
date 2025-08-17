<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\kereta;

class KeretaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            'Argo Parahyangan', 'Lodaya', 'Taksaka', 'Gajayana', 'Brawijaya',
            'Sancaka', 'Matarmaja', 'Malabar', 'Turangga', 'Serayu',
            'Kertajaya', 'Majapahit', 'Jayabaya', 'Brantas', 'Kutojaya Utara',
            'Kutojaya Selatan', 'Progo', 'Bogowonto', 'Singasari', 'Kahuripan'
        ];
        $types = ['Ekonomi', 'Bisnis', 'Eksekutif'];
        $status = ['Aktif','Nonaktif'];

        $date = now()->format('Ymd');

        foreach ($names as $i => $name) {
            kereta::create([
                'Kode_Kereta' => 'KRT' . $date . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'nama_kereta' => $name,
                'kapasitas' => rand(200, 400),
                'tipe' => $types[array_rand($types)],
                'status' => $status[array_rand($status)],
                'foto_kereta' => 'img/kereta-' . ($i + 1) . '.jpg',
            ]);
        }
    }
}

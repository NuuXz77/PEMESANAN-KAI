<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $date = now()->format('Ymd');
        $kode_akun = 'USRKAI' . $date . str_pad(1, 5, '0', STR_PAD_LEFT);

        User::create([
            'Kode_Akun' => $kode_akun,
            'nama' => 'Admin KAI',
            'email' => 'admin@kai.id',
            'password' => Hash::make('admin123'),
            'nik' => 1234567890123456,
            'nomor_telp' => 6281234567890,
            'status' => 'aktif',
            'role' => 'admin',
            'tanggal_dibuat' => now()->toDateString(),
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\stasiun;

class StasiunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stations = [
            ['Kode_Stasiun' => 'STJAKARTA', 'nama_stasiun' => 'Gambir', 'kota' => 'Jakarta', 'alamat' => 'Jl. Medan Merdeka Timur', 'latitude' => -6.176655, 'longitude' => 106.830583, 'foto_stasiun' => 'img/stasiun-gambir.jpg'],
            ['Kode_Stasiun' => 'STBANDUNG', 'nama_stasiun' => 'Bandung', 'kota' => 'Bandung', 'alamat' => 'Jl. Stasiun Barat', 'latitude' => -6.917464, 'longitude' => 107.618782, 'foto_stasiun' => 'img/stasiun-bandung.jpg'],
            ['Kode_Stasiun' => 'STSURABAYA', 'nama_stasiun' => 'Surabaya Gubeng', 'kota' => 'Surabaya', 'alamat' => 'Jl. Gubeng Masjid', 'latitude' => -7.263056, 'longitude' => 112.752222, 'foto_stasiun' => 'img/stasiun-gubeng.jpg'],
            ['Kode_Stasiun' => 'STYOGYAKARTA', 'nama_stasiun' => 'Yogyakarta', 'kota' => 'Yogyakarta', 'alamat' => 'Jl. Pasar Kembang', 'latitude' => -7.789556, 'longitude' => 110.367778, 'foto_stasiun' => 'img/stasiun-yogyakarta.jpg'],
            ['Kode_Stasiun' => 'STSEMARANG', 'nama_stasiun' => 'Semarang Tawang', 'kota' => 'Semarang', 'alamat' => 'Jl. Tawang', 'latitude' => -6.966667, 'longitude' => 110.428611, 'foto_stasiun' => 'img/stasiun-tawang.jpg'],
            ['Kode_Stasiun' => 'STMALANG', 'nama_stasiun' => 'Malang', 'kota' => 'Malang', 'alamat' => 'Jl. Trunojoyo', 'latitude' => -7.977778, 'longitude' => 112.633333, 'foto_stasiun' => 'img/stasiun-malang.jpg'],
            ['Kode_Stasiun' => 'STCIREBON', 'nama_stasiun' => 'Cirebon', 'kota' => 'Cirebon', 'alamat' => 'Jl. Siliwangi', 'latitude' => -6.705556, 'longitude' => 108.561111, 'foto_stasiun' => 'img/stasiun-cirebon.jpg'],
            ['Kode_Stasiun' => 'STTASIK', 'nama_stasiun' => 'Tasikmalaya', 'kota' => 'Tasikmalaya', 'alamat' => 'Jl. Stasiun Tasik', 'latitude' => -7.327778, 'longitude' => 108.220833, 'foto_stasiun' => 'img/stasiun-tasikmalaya.jpg'],
            ['Kode_Stasiun' => 'STBOGOR', 'nama_stasiun' => 'Bogor', 'kota' => 'Bogor', 'alamat' => 'Jl. Nyi Raja Permas', 'latitude' => -6.594444, 'longitude' => 106.789444, 'foto_stasiun' => 'img/stasiun-bogor.jpg'],
            ['Kode_Stasiun' => 'STSOLO', 'nama_stasiun' => 'Solo Balapan', 'kota' => 'Solo', 'alamat' => 'Jl. Wolter Monginsidi', 'latitude' => -7.565833, 'longitude' => 110.817222, 'foto_stasiun' => 'img/stasiun-solo.jpg'],
            ['Kode_Stasiun' => 'STPURWOKERTO', 'nama_stasiun' => 'Purwokerto', 'kota' => 'Purwokerto', 'alamat' => 'Jl. Stasiun', 'latitude' => -7.426389, 'longitude' => 109.241389, 'foto_stasiun' => 'img/stasiun-purwokerto.jpg'],
            ['Kode_Stasiun' => 'STJEMBER', 'nama_stasiun' => 'Jember', 'kota' => 'Jember', 'alamat' => 'Jl. Wijaya Kusuma', 'latitude' => -8.172222, 'longitude' => 113.703333, 'foto_stasiun' => 'img/stasiun-jember.jpg'],
            ['Kode_Stasiun' => 'STKEDIRI', 'nama_stasiun' => 'Kediri', 'kota' => 'Kediri', 'alamat' => 'Jl. Stasiun Kediri', 'latitude' => -7.816667, 'longitude' => 112.011111, 'foto_stasiun' => 'img/stasiun-kediri.jpg'],
            ['Kode_Stasiun' => 'STMADIUN', 'nama_stasiun' => 'Madiun', 'kota' => 'Madiun', 'alamat' => 'Jl. Kompol Sunaryo', 'latitude' => -7.629444, 'longitude' => 111.523333, 'foto_stasiun' => 'img/stasiun-madiun.jpg'],
            ['Kode_Stasiun' => 'STPEKALONGAN', 'nama_stasiun' => 'Pekalongan', 'kota' => 'Pekalongan', 'alamat' => 'Jl. Stasiun', 'latitude' => -6.888333, 'longitude' => 109.675833, 'foto_stasiun' => 'img/stasiun-pekalongan.jpg'],
            ['Kode_Stasiun' => 'STTULUNGAGUNG', 'nama_stasiun' => 'Tulungagung', 'kota' => 'Tulungagung', 'alamat' => 'Jl. Stasiun', 'latitude' => -8.065833, 'longitude' => 111.902222, 'foto_stasiun' => 'img/stasiun-tulungagung.jpg'],
            ['Kode_Stasiun' => 'STBLITAR', 'nama_stasiun' => 'Blitar', 'kota' => 'Blitar', 'alamat' => 'Jl. Mastrip', 'latitude' => -8.100833, 'longitude' => 112.167222, 'foto_stasiun' => 'img/stasiun-blitar.jpg'],
            ['Kode_Stasiun' => 'STGARUT', 'nama_stasiun' => 'Garut', 'kota' => 'Garut', 'alamat' => 'Jl. Stasiun Garut', 'latitude' => -7.220833, 'longitude' => 107.910833, 'foto_stasiun' => 'img/stasiun-garut.jpg'],
            ['Kode_Stasiun' => 'STCILACAP', 'nama_stasiun' => 'Cilacap', 'kota' => 'Cilacap', 'alamat' => 'Jl. Stasiun Cilacap', 'latitude' => -7.722222, 'longitude' => 109.013333, 'foto_stasiun' => 'img/stasiun-cilacap.jpg'],
            ['Kode_Stasiun' => 'STSRAGEN', 'nama_stasiun' => 'Sragen', 'kota' => 'Sragen', 'alamat' => 'Jl. Stasiun Sragen', 'latitude' => -7.426389, 'longitude' => 111.021111, 'foto_stasiun' => 'img/stasiun-sragen.jpg'],
        ];

        foreach ($stations as $i => $st) {
            stasiun::create(array_merge($st, ['ID_Stasiun' => $i + 1]));
        }
    }
}

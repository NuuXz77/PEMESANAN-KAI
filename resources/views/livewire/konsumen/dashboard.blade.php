<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] #[Title('Dashboard')] class extends Component {
    // Extended dummy static data (added 10 more entries)
    public $stasiun = [
        ['ID_Stasiun' => 1, 'Kode_Stasiun' => 'STJAKARTA', 'nama_stasiun' => 'Gambir', 'kota' => 'Jakarta', 'alamat' => 'Jl. Medan Merdeka Timur'],
        ['ID_Stasiun' => 2, 'Kode_Stasiun' => 'STBANDUNG', 'nama_stasiun' => 'Bandung', 'kota' => 'Bandung', 'alamat' => 'Jl. Stasiun Barat'],
        ['ID_Stasiun' => 3, 'Kode_Stasiun' => 'STTASIKMALAYA', 'nama_stasiun' => 'Tasikmalaya', 'kota' => 'Tasikmalaya', 'alamat' => 'Jl. Stasiun Tasik'],
        ['ID_Stasiun' => 4, 'Kode_Stasiun' => 'STJOGJA', 'nama_stasiun' => 'Yogyakarta', 'kota' => 'Yogyakarta', 'alamat' => 'Jl. Pasar Kembang'],
        ['ID_Stasiun' => 5, 'Kode_Stasiun' => 'STSOLO', 'nama_stasiun' => 'Solo Balapan', 'kota' => 'Solo', 'alamat' => 'Jl. Wolter Monginsidi'],
        ['ID_Stasiun' => 6, 'Kode_Stasiun' => 'STSURABAYA', 'nama_stasiun' => 'Surabaya Gubeng', 'kota' => 'Surabaya', 'alamat' => 'Jl. Gubeng Masjid'],
        ['ID_Stasiun' => 7, 'Kode_Stasiun' => 'STMALANG', 'nama_stasiun' => 'Malang', 'kota' => 'Malang', 'alamat' => 'Jl. Trunojoyo'],
        ['ID_Stasiun' => 8, 'Kode_Stasiun' => 'STSEMARANG', 'nama_stasiun' => 'Semarang Tawang', 'kota' => 'Semarang', 'alamat' => 'Jl. Taman Tawang'],
        ['ID_Stasiun' => 9, 'Kode_Stasiun' => 'STPADANG', 'nama_stasiun' => 'Padang', 'kota' => 'Padang', 'alamat' => 'Jl. Perintis Kemerdekaan'],
        ['ID_Stasiun' => 10, 'Kode_Stasiun' => 'STMEDAN', 'nama_stasiun' => 'Medan', 'kota' => 'Medan', 'alamat' => 'Jl. Stasiun Medan'],
        ['ID_Stasiun' => 11, 'Kode_Stasiun' => 'STPALEMBANG', 'nama_stasiun' => 'Palembang', 'kota' => 'Palembang', 'alamat' => 'Jl. Letkol Iskandar'],
        ['ID_Stasiun' => 12, 'Kode_Stasiun' => 'STBALI', 'nama_stasiun' => 'Denpasar', 'kota' => 'Bali', 'alamat' => 'Jl. Diponegoro'],
        ['ID_Stasiun' => 13, 'Kode_Stasiun' => 'STMAKASSAR', 'nama_stasiun' => 'Makassar', 'kota' => 'Makassar', 'alamat' => 'Jl. Metro Tanjung Bunga'],
    ];

    public $rute = [
        ['ID_Rute' => 1, 'Kode_Rute' => 'RT001', 'asal_id' => 1, 'tujuan_id' => 2, 'jarak_tempuh' => 150, 'durasi' => '03:00'],
        ['ID_Rute' => 2, 'Kode_Rute' => 'RT002', 'asal_id' => 2, 'tujuan_id' => 3, 'jarak_tempuh' => 120, 'durasi' => '02:30'],
        ['ID_Rute' => 3, 'Kode_Rute' => 'RT003', 'asal_id' => 1, 'tujuan_id' => 3, 'jarak_tempuh' => 270, 'durasi' => '05:30'],
        ['ID_Rute' => 4, 'Kode_Rute' => 'RT004', 'asal_id' => 1, 'tujuan_id' => 4, 'jarak_tempuh' => 500, 'durasi' => '08:00'],
        ['ID_Rute' => 5, 'Kode_Rute' => 'RT005', 'asal_id' => 2, 'tujuan_id' => 4, 'jarak_tempuh' => 350, 'durasi' => '05:00'],
        ['ID_Rute' => 6, 'Kode_Rute' => 'RT006', 'asal_id' => 4, 'tujuan_id' => 5, 'jarak_tempuh' => 100, 'durasi' => '01:30'],
        ['ID_Rute' => 7, 'Kode_Rute' => 'RT007', 'asal_id' => 4, 'tujuan_id' => 6, 'jarak_tempuh' => 400, 'durasi' => '06:00'],
        ['ID_Rute' => 8, 'Kode_Rute' => 'RT008', 'asal_id' => 5, 'tujuan_id' => 7, 'jarak_tempuh' => 150, 'durasi' => '02:30'],
        ['ID_Rute' => 9, 'Kode_Rute' => 'RT009', 'asal_id' => 6, 'tujuan_id' => 7, 'jarak_tempuh' => 120, 'durasi' => '02:00'],
        ['ID_Rute' => 10, 'Kode_Rute' => 'RT010', 'asal_id' => 1, 'tujuan_id' => 6, 'jarak_tempuh' => 800, 'durasi' => '12:00'],
        ['ID_Rute' => 11, 'Kode_Rute' => 'RT011', 'asal_id' => 3, 'tujuan_id' => 8, 'jarak_tempuh' => 450, 'durasi' => '07:00'],
        ['ID_Rute' => 12, 'Kode_Rute' => 'RT012', 'asal_id' => 8, 'tujuan_id' => 9, 'jarak_tempuh' => 600, 'durasi' => '09:00'],
        ['ID_Rute' => 13, 'Kode_Rute' => 'RT013', 'asal_id' => 9, 'tujuan_id' => 10, 'jarak_tempuh' => 300, 'durasi' => '05:00'],
    ];

    public $kereta = [
        ['ID_Kereta' => 1, 'Kode_Kereta' => 'KRT20240601001', 'nama_kereta' => 'Argo Parahyangan', 'kapasitas' => 300, 'tipe' => 'Eksekutif'],
        ['ID_Kereta' => 2, 'Kode_Kereta' => 'KRT20240601002', 'nama_kereta' => 'Lodaya', 'kapasitas' => 250, 'tipe' => 'Bisnis'],
        ['ID_Kereta' => 3, 'Kode_Kereta' => 'KRT20240601003', 'nama_kereta' => 'Sancaka', 'kapasitas' => 350, 'tipe' => 'Eksekutif'],
        ['ID_Kereta' => 4, 'Kode_Kereta' => 'KRT20240601004', 'nama_kereta' => 'Gajayana', 'kapasitas' => 280, 'tipe' => 'Eksekutif'],
        ['ID_Kereta' => 5, 'Kode_Kereta' => 'KRT20240601005', 'nama_kereta' => 'Bima', 'kapasitas' => 320, 'tipe' => 'Eksekutif'],
        ['ID_Kereta' => 6, 'Kode_Kereta' => 'KRT20240601006', 'nama_kereta' => 'Taksaka', 'kapasitas' => 290, 'tipe' => 'Eksekutif'],
        ['ID_Kereta' => 7, 'Kode_Kereta' => 'KRT20240601007', 'nama_kereta' => 'Mutiara Selatan', 'kapasitas' => 270, 'tipe' => 'Bisnis'],
        ['ID_Kereta' => 8, 'Kode_Kereta' => 'KRT20240601008', 'nama_kereta' => 'Sembrani', 'kapasitas' => 310, 'tipe' => 'Eksekutif'],
        ['ID_Kereta' => 9, 'Kode_Kereta' => 'KRT20240601009', 'nama_kereta' => 'Harina', 'kapasitas' => 260, 'tipe' => 'Eksekutif'],
        ['ID_Kereta' => 10, 'Kode_Kereta' => 'KRT20240601010', 'nama_kereta' => 'Jayakarta', 'kapasitas' => 240, 'tipe' => 'Bisnis'],
        ['ID_Kereta' => 11, 'Kode_Kereta' => 'KRT20240601011', 'nama_kereta' => 'Kertajaya', 'kapasitas' => 230, 'tipe' => 'Bisnis'],
        ['ID_Kereta' => 12, 'Kode_Kereta' => 'KRT20240601012', 'nama_kereta' => 'Bangunkarta', 'kapasitas' => 330, 'tipe' => 'Eksekutif'],
    ];

    public $jadwal = [
        [
            'ID_Jadwal' => 1,
            'Kode_Jadwal' => 'JDWL20240601KRT20240601001RT0010001',
            'id_kereta' => 1,
            'id_rute' => 1,
            'waktu_keberangkatan' => '2024-06-01 08:00',
            'waktu_kedatangan' => '2024-06-01 11:00',
        ],
        [
            'ID_Jadwal' => 2,
            'Kode_Jadwal' => 'JDWL20240601KRT20240601002RT0020002',
            'id_kereta' => 2,
            'id_rute' => 2,
            'waktu_keberangkatan' => '2024-06-01 13:00',
            'waktu_kedatangan' => '2024-06-01 15:30',
        ],
        [
            'ID_Jadwal' => 3,
            'Kode_Jadwal' => 'JDWL20240601KRT20240601003RT0030003',
            'id_kereta' => 3,
            'id_rute' => 3,
            'waktu_keberangkatan' => '2024-06-01 09:00',
            'waktu_kedatangan' => '2024-06-01 14:30',
        ],
        [
            'ID_Jadwal' => 4,
            'Kode_Jadwal' => 'JDWL20240601KRT20240601004RT0040004',
            'id_kereta' => 4,
            'id_rute' => 4,
            'waktu_keberangkatan' => '2024-06-01 07:00',
            'waktu_kedatangan' => '2024-06-01 15:00',
        ],
        [
            'ID_Jadwal' => 5,
            'Kode_Jadwal' => 'JDWL20240601KRT20240601005RT0050005',
            'id_kereta' => 5,
            'id_rute' => 5,
            'waktu_keberangkatan' => '2024-06-01 10:00',
            'waktu_kedatangan' => '2024-06-01 15:00',
        ],
        [
            'ID_Jadwal' => 6,
            'Kode_Jadwal' => 'JDWL20240601KRT20240601006RT0060006',
            'id_kereta' => 6,
            'id_rute' => 6,
            'waktu_keberangkatan' => '2024-06-01 14:00',
            'waktu_kedatangan' => '2024-06-01 15:30',
        ],
        [
            'ID_Jadwal' => 7,
            'Kode_Jadwal' => 'JDWL20240601KRT20240601007RT0070007',
            'id_kereta' => 7,
            'id_rute' => 7,
            'waktu_keberangkatan' => '2024-06-01 16:00',
            'waktu_kedatangan' => '2024-06-01 22:00',
        ],
        [
            'ID_Jadwal' => 8,
            'Kode_Jadwal' => 'JDWL20240601KRT20240601008RT0080008',
            'id_kereta' => 8,
            'id_rute' => 8,
            'waktu_keberangkatan' => '2024-06-01 08:30',
            'waktu_kedatangan' => '2024-06-01 11:00',
        ],
        [
            'ID_Jadwal' => 9,
            'Kode_Jadwal' => 'JDWL20240601KRT20240601009RT0090009',
            'id_kereta' => 9,
            'id_rute' => 9,
            'waktu_keberangkatan' => '2024-06-01 12:00',
            'waktu_kedatangan' => '2024-06-01 14:00',
        ],
        [
            'ID_Jadwal' => 10,
            'Kode_Jadwal' => 'JDWL20240601KRT20240601010RT0100010',
            'id_kereta' => 10,
            'id_rute' => 10,
            'waktu_keberangkatan' => '2024-06-01 06:00',
            'waktu_kedatangan' => '2024-06-01 18:00',
        ],
        [
            'ID_Jadwal' => 11,
            'Kode_Jadwal' => 'JDWL20240601KRT20240601011RT0110011',
            'id_kereta' => 11,
            'id_rute' => 11,
            'waktu_keberangkatan' => '2024-06-01 09:30',
            'waktu_kedatangan' => '2024-06-01 16:30',
        ],
        [
            'ID_Jadwal' => 12,
            'Kode_Jadwal' => 'JDWL20240601KRT20240601012RT0120012',
            'id_kereta' => 12,
            'id_rute' => 12,
            'waktu_keberangkatan' => '2024-06-01 07:30',
            'waktu_kedatangan' => '2024-06-01 16:30',
        ],
        [
            'ID_Jadwal' => 13,
            'Kode_Jadwal' => 'JDWL20240601KRT20240601012RT0120013',
            'id_kereta' => 12,
            'id_rute' => 12,
            'waktu_keberangkatan' => '2024-06-01 07:30',
            'waktu_kedatangan' => '2024-06-01 16:30',
        ],
        [
            'ID_Jadwal' => 14,
            'Kode_Jadwal' => 'JDWL20240601KRT20240601012RT0120014',
            'id_kereta' => 12,
            'id_rute' => 12,
            'waktu_keberangkatan' => '2024-06-01 07:30',
            'waktu_kedatangan' => '2024-06-01 16:30',
        ],
    ];

    // Form properties
    public $asal_id = null;
    public $tujuan_id = null;
    public $tanggal = '';
    public $penumpang = 1;

    // Search results
    public $hasilPencarian = [];

    public function search()
    {
        // Validate inputs
        if (empty($this->asal_id) || empty($this->tujuan_id) || empty($this->tanggal)) {
            $this->hasilPencarian = [];
            return;
        }

        // Simulate loading
        $this->hasilPencarian = null;

        // Filter schedules by route origin & destination
        $this->hasilPencarian = collect($this->jadwal)
            ->filter(function ($jadwal) {
                $rute = collect($this->rute)->firstWhere('ID_Rute', $jadwal['id_rute']);
                return $rute && $rute['asal_id'] == $this->asal_id && $rute['tujuan_id'] == $this->tujuan_id;
            })
            ->map(function ($jadwal) {
                $rute = collect($this->rute)->firstWhere('ID_Rute', $jadwal['id_rute']);
                $kereta = collect($this->kereta)->firstWhere('ID_Kereta', $jadwal['id_kereta']);
                $asal = collect($this->stasiun)->firstWhere('ID_Stasiun', $rute['asal_id']);
                $tujuan = collect($this->stasiun)->firstWhere('ID_Stasiun', $rute['tujuan_id']);

                // Calculate price based on distance and train type
                $hargaPerKm = $kereta['tipe'] === 'Eksekutif' ? 2000 : 1500;
                $harga = $rute['jarak_tempuh'] * $hargaPerKm;

                return [
                    'jadwal' => $jadwal,
                    'kereta' => $kereta,
                    'rute' => $rute,
                    'asal' => $asal,
                    'tujuan' => $tujuan,
                    'harga' => $harga,
                ];
            })
            ->values()
            ->toArray();
    }
}; ?>

<div class="max-w-5xl mx-auto py-10 px-4">
    <!-- Title -->
    <div class="text-center mb-8">
        <h1 class="text-3xl md:text-4xl font-bold mb-2 text-primary-content">
            Selamat Datang, {{ auth()->user()->nama }}
        </h1>
        <p class="text-base-content text-base md:text-lg">
            Temukan dan pesan tiket kereta dengan mudah.
        </p>
    </div>

    <!-- Search Bar -->
    <form wire:submit.prevent="search"
        class="bg-primary/10 rounded-xl p-4 mb-6 transition-all duration-300 hover:shadow-md">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Origin Station -->
            <div>
                <x-choices-offline wire:model="asal_id" :options="$stasiun" option-label="nama_stasiun"
                    option-value="ID_Stasiun" option-description="kota" placeholder="Stasiun Asal" searchable clearable
                    single class="w-full" />
            </div>

            <!-- Destination Station -->
            <div>
                <x-choices-offline wire:model="tujuan_id" :options="$stasiun" option-label="nama_stasiun"
                    option-value="ID_Stasiun" option-description="kota" placeholder="Stasiun Tujuan" searchable
                    clearable single class="w-full" />
            </div>

            <!-- Date -->
            <div>
                <x-datetime wire:model="tanggal" placeholder="Tanggal Berangkat" type="date" icon="o-calendar"
                    min="{{ now()->format('Y-m-d') }}" class="w-full" />
            </div>

            <!-- Passenger Count -->
            <div>
                <x-input wire:model="penumpang" type="number" placeholder="Jumlah Penumpang" min="1"
                    max="100" icon="o-users" class="w-full" />
            </div>

            <!-- Search Button -->
            <div class="flex items-center justify-center">
                <x-button type="submit" icon="o-magnifying-glass" class="bg-primary w-full" />
            </div>
        </div>
    </form>

    <!-- Search Results -->
    <div class="mb-4">
        <div class="bg-primary text-primary-content font-semibold rounded-lg px-4 py-2 text-lg">Hasil Pencarian</div>
    </div>

    <div class="space-y-4">
        @if ($hasilPencarian === null)
            <!-- Loading state -->
            <div class="flex justify-center items-center py-10">
                <x-loading class="text-primary loading-lg" />
                <span class="ml-2">Mencari jadwal...</span>
            </div>
        @elseif(empty($hasilPencarian))
            <!-- No results -->
            <div class="bg-warning/20 text-warning-content rounded-lg px-4 py-8 text-center">
                <x-icon name="o-exclamation-triangle" class="w-10 h-10 mx-auto mb-2" />
                <p class="font-semibold">Tidak ditemukan jadwal kereta untuk rute yang dipilih.</p>
                <p class="text-sm">Silakan coba dengan rute atau tanggal yang berbeda.</p>
            </div>
        @else
            <!-- Results -->
            @foreach ($hasilPencarian as $result)
                <div class="bg-base-200 rounded-lg shadow-sm border px-6 py-4 flex flex-col justify-between items-start transition-all duration-300 hover:shadow-md hover:border-primary/50"
                    x-data="{ showDetails: false }">
                    <!-- Main Info Row -->
                    <div class="w-full flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <!-- Train Info -->
                        <div class="lg:w-2/3">
                            <div class="flex flex-col md:flex-row md:items-center gap-4 mb-2">
                                <div class="font-bold text-primary text-lg">{{ $result['kereta']['nama_kereta'] }}</div>
                                <div
                                    class="badge badge-{{ $result['kereta']['tipe'] === 'Eksekutif' ? 'primary' : 'secondary' }}">
                                    {{ $result['kereta']['tipe'] }}
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                                <div>
                                    <div class="text-sm text-base-content/70">Keberangkatan</div>
                                    <div class="font-medium">
                                        {{ \Carbon\Carbon::parse($result['jadwal']['waktu_keberangkatan'])->format('H:i') }}
                                    </div>
                                    <div class="text-sm">{{ $result['asal']['nama_stasiun'] }}</div>
                                </div>

                                <div class="flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-xs text-base-content/50">
                                            {{ $result['rute']['durasi'] }} • {{ $result['rute']['jarak_tempuh'] }} km
                                        </div>
                                        <div class="h-px bg-base-content/20 w-full my-1"></div>
                                        <div class="text-xs text-base-content/50">
                                            {{ $result['asal']['kota'] }} → {{ $result['tujuan']['kota'] }}
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="text-sm text-base-content/70">Kedatangan</div>
                                    <div class="font-medium">
                                        {{ \Carbon\Carbon::parse($result['jadwal']['waktu_kedatangan'])->format('H:i') }}
                                    </div>
                                    <div class="text-sm">{{ $result['tujuan']['nama_stasiun'] }}</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 text-sm">
                                <x-icon name="o-user" class="w-4 h-4" />
                                <span class="text-success font-semibold">{{ $result['kereta']['kapasitas'] }} kursi
                                    tersedia</span>
                            </div>
                        </div>

                        <!-- Price & Button -->
                        <div class="lg:w-1/3 mt-4 lg:mt-0 lg:pl-8 lg:text-right">
                            <div class="font-bold text-warning text-xl mb-2">
                                Rp {{ number_format($result['harga'], 0, ',', '.') }}
                            </div>
                            <button class="btn btn-primary btn-sm w-full lg:w-auto" @click="showDetails = !showDetails">
                                <x-icon name="o-eye" class="w-4 h-4 mr-1" />
                                <span>Detail</span>
                            </button>
                        </div>
                    </div>

                    <!-- Details section - Will appear below on all screens -->
                    <div class="w-full mt-4 pt-4 border-t border-base-content/10" x-show="showDetails" x-transition>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <h3 class="font-semibold mb-3 text-lg">Informasi Kereta</h3>
                                <ul class="space-y-2 text-base">
                                    <li class="flex items-center gap-3">
                                        <x-icon name="o-ticket" class="w-5 h-5" />
                                        <span>Kode: {{ $result['kereta']['Kode_Kereta'] }}</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <x-icon name="o-users" class="w-5 h-5" />
                                        <span>Kapasitas: {{ $result['kereta']['kapasitas'] }} penumpang</span>
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="font-semibold mb-3 text-lg">Informasi Rute</h3>
                                <ul class="space-y-2 text-base">
                                    <li class="flex items-center gap-3">
                                        <x-icon name="o-map-pin" class="w-5 h-5" />
                                        <span>Asal: {{ $result['asal']['nama_stasiun'] }},
                                            {{ $result['asal']['kota'] }}</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <x-icon name="o-flag" class="w-5 h-5" />
                                        <span>Tujuan: {{ $result['tujuan']['nama_stasiun'] }},
                                            {{ $result['tujuan']['kota'] }}</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <x-icon name="o-clock" class="w-5 h-5" />
                                        <span>Durasi: {{ $result['rute']['durasi'] }}
                                            ({{ $result['rute']['jarak_tempuh'] }} km)</span>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button class="btn btn-primary btn-lg">
                                <x-icon name="o-shopping-cart" class="w-5 h-5 mr-2" />
                                Pesan Sekarang
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use App\Models\Stasiun;
use App\Models\Jadwal;
use Carbon\Carbon;

new #[Layout('components.layouts.app')] #[Title('Dashboard')] class extends Component {
    // Form properties
    public $asal_id = null;
    public $tujuan_id = null;
    public $tanggal = '';
    public $penumpang = 1;

    // Search state
    public $isSearching = false;
    public $searchError = '';
    public $hasilPencarian = [];

    // Get station data from database
    public function stasiun()
    {
        return Stasiun::orderBy('nama_stasiun')->get();
    }

    // Search function with loading state
    public function search()
    {
        // Reset previous state
        $this->reset('searchError', 'hasilPencarian');

        // Basic validation
        if (empty($this->asal_id)) {
            $this->searchError = 'Silakan pilih stasiun asal';
            return;
        }

        if (empty($this->tujuan_id)) {
            $this->searchError = 'Silakan pilih stasiun tujuan';
            return;
        }

        if ($this->asal_id == $this->tujuan_id) {
            $this->searchError = 'Stasiun tujuan harus berbeda dengan stasiun asal';
            return;
        }

        if (empty($this->tanggal)) {
            $this->searchError = 'Silakan pilih tanggal keberangkatan';
            return;
        }

        if (Carbon::parse($this->tanggal)->isBefore(now()->startOfDay())) {
            $this->searchError = 'Tanggal tidak boleh di masa lalu';
            return;
        }

        // Set loading state
        $this->isSearching = true;

        try {
            // Search schedules in database
            $this->hasilPencarian = Jadwal::with(['kereta', 'rute.asal', 'rute.tujuan'])
                ->whereHas('rute', function ($query) {
                    $query->where('asal_id', $this->asal_id)->where('tujuan_id', $this->tujuan_id);
                })
                ->take(5)
                ->whereDate('waktu_keberangkatan', $this->tanggal)
                ->get()
                ->map(function ($jadwal) {
                    // Calculate price based on distance and train type
                    $hargaPerKm = $jadwal->kereta->tipe === 'Eksekutif' ? 2000 : 1500;
                    $harga = $jadwal->rute->jarak_tempuh * $hargaPerKm;

                    return [
                        'jadwal' => $jadwal,
                        'kereta' => $jadwal->kereta,
                        'rute' => $jadwal->rute,
                        'asal' => $jadwal->rute->asal,
                        'tujuan' => $jadwal->rute->tujuan,
                        'harga' => $harga,
                    ];
                })
                ->toArray();
        } catch (\Exception $e) {
            $this->searchError = 'Terjadi kesalahan saat mencari jadwal';
        } finally {
            $this->isSearching = false;
        }
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
    <x-form wire:submit.prevent="search"
        class="bg-primary/10 rounded-xl p-4 mb-6 transition-all duration-300 hover:shadow-md">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Origin Station -->
            <div class="relative">
                <x-choices-offline wire:model="asal_id" :options="$this->stasiun()" option-label="nama_stasiun"
                    option-value="ID_Stasiun" option-description="kota" placeholder="Stasiun Asal" single clearable
                    searchable icon="o-map-pin" class="w-full" />
            </div>

            <!-- Destination Station -->
            <div class="relative">
                <x-choices-offline wire:model="tujuan_id" :options="$this->stasiun()" option-label="nama_stasiun"
                    option-value="ID_Stasiun" option-description="kota" placeholder="Stasiun Tujuan" searchable
                    clearable single icon="o-flag" class="w-full" />
            </div>

            <!-- Date -->
            <div class="relative">
                <x-datetime wire:model="tanggal" placeholder="Tanggal Berangkat" type="date" icon="o-calendar"
                    min="{{ now()->format('Y-m-d') }}" class="w-full" />
            </div>

            <!-- Passenger Count -->
            <div class="relative">
                <x-input wire:model="penumpang" type="number" placeholder="Jumlah Penumpang" min="1"
                    max="10" icon="o-users" class="w-full" />
            </div>

            <!-- Search Button -->
            <div class="flex items-center justify-center">
                <x-button type="submit" icon="o-magnifying-glass" class="bg-primary w-full md:w-auto"
                    wire:loading.attr="disabled" wire:loading.class="loading">
                    <span class="hidden md:inline">Cari</span>
                </x-button>
            </div>
        </div>

        <!-- Error message -->
        @if ($searchError)
            <div class="mt-4 text-error text-sm text-center">
                <x-icon name="o-exclamation-circle" class="w-5 h-5 inline mr-1" />
                {{ $searchError }}
            </div>
        @endif
    </x-form>

    <!-- Search Results -->
    <div class="mb-4">
        <div class="bg-primary text-primary-content font-semibold rounded-lg px-4 py-2 text-lg flex items-center">
            <x-icon name="o-list-bullet" class="w-5 h-5 mr-2" />
            <span>Hasil Pencarian</span>
        </div>
    </div>

    <div class="space-y-4">
        @if ($isSearching)
            <!-- Loading state with progress bar -->
            <div class="space-y-4">
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div class="bg-primary h-2.5 rounded-full animate-pulse" x-data="{ width: 0 }"
                        x-init="setInterval(() => { width = (width + 10) % 100 }, 300)" :style="'width: ' + width + '%'"></div>
                </div>
                <div class="flex justify-center items-center py-10">
                    <x-loading class="text-primary loading-lg" />
                    <span class="ml-2">Mencari jadwal...</span>
                </div>
            </div>
        @elseif(empty($hasilPencarian) && ($asal_id || $tujuan_id || $tanggal) && !$isSearching)
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
                                <div class="font-bold text-primary text-lg flex items-center">
                                    <x-icon name="o-train" class="w-5 h-5 mr-2" />
                                    {{ $result['kereta']->nama_kereta }}
                                </div>
                                <div
                                    class="badge badge-{{ $result['kereta']->tipe === 'Eksekutif' ? 'primary' : 'secondary' }}">
                                    {{ $result['kereta']->tipe }}
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                                <div>
                                    <div class="text-sm text-base-content/70 flex items-center">
                                        <x-icon name="o-arrow-right" class="w-4 h-4 mr-1" />
                                        Keberangkatan
                                    </div>
                                    <div class="font-medium">
                                        {{ $result['jadwal']->waktu_keberangkatan->format('H:i') }}
                                    </div>
                                    <div class="text-sm">{{ $result['asal']->nama_stasiun }}</div>
                                </div>

                                <div class="flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="text-xs text-base-content/50 flex items-center justify-center">
                                            <x-icon name="o-clock" class="w-3 h-3 mr-1" />
                                            {{ $result['rute']->durasi }}
                                        </div>
                                        <div class="h-px bg-base-content/20 w-full my-1"></div>
                                        <div class="text-xs text-base-content/50 flex items-center justify-center">
                                            <x-icon name="o-map-pin" class="w-3 h-3 mr-1" />
                                            {{ $result['rute']->jarak_tempuh }} km
                                        </div>
                                        <div class="h-px bg-base-content/20 w-full my-1"></div>
                                        <div class="text-xs text-base-content/50">
                                            {{ $result['asal']->kota }} → {{ $result['tujuan']->kota }}
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="text-sm text-base-content/70 flex items-center">
                                        <x-icon name="o-flag" class="w-4 h-4 mr-1" />
                                        Kedatangan
                                    </div>
                                    <div class="font-medium">
                                        {{ $result['jadwal']->waktu_kedatangan->format('H:i') }}
                                    </div>
                                    <div class="text-sm">{{ $result['tujuan']->nama_stasiun }}</div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 text-sm">
                                <x-icon name="o-user" class="w-4 h-4" />
                                <span class="text-success font-semibold">{{ $result['kereta']->kapasitas }} kursi
                                    tersedia</span>
                            </div>
                        </div>

                        <!-- Price & Button -->
                        <div class="lg:w-1/3 mt-4 lg:mt-0 lg:pl-8 lg:text-right">
                            <div class="font-bold text-warning text-xl mb-2 flex items-center justify-end">
                                <x-icon name="o-banknotes" class="w-5 h-5 mr-1" />
                                Rp {{ number_format($result['harga'], 0, ',', '.') }}
                            </div>
                            <button class="btn btn-primary btn-sm w-full lg:w-auto"
                                @click="showDetails = !showDetails">
                                <x-icon name="o-eye" class="w-4 h-4 mr-1" />
                                <span>Detail</span>
                            </button>
                        </div>
                    </div>

                    <!-- Details section -->
                    <div class="w-full mt-4 pt-4 border-t border-base-content/10" x-show="showDetails" x-transition>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <h3 class="font-semibold mb-3 text-lg flex items-center">
                                    <x-icon name="o-information-circle" class="w-5 h-5 mr-2" />
                                    Informasi Kereta
                                </h3>
                                <ul class="space-y-2 text-base">
                                    <li class="flex items-center gap-3">
                                        <x-icon name="o-ticket" class="w-5 h-5" />
                                        <span>Kode: {{ $result['kereta']->Kode_Kereta }}</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <x-icon name="o-users" class="w-5 h-5" />
                                        <span>Kapasitas: {{ $result['kereta']->kapasitas }} penumpang</span>
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <h3 class="font-semibold mb-3 text-lg flex items-center">
                                    <x-icon name="o-map" class="w-5 h-5 mr-2" />
                                    Informasi Rute
                                </h3>
                                <ul class="space-y-2 text-base">
                                    <li class="flex items-center gap-3">
                                        <x-icon name="o-map-pin" class="w-5 h-5" />
                                        <span>Asal: {{ $result['asal']->nama_stasiun }},
                                            {{ $result['asal']->kota }}</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <x-icon name="o-flag" class="w-5 h-5" />
                                        <span>Tujuan: {{ $result['tujuan']->nama_stasiun }},
                                            {{ $result['tujuan']->kota }}</span>
                                    </li>
                                    <li class="flex items-center gap-3">
                                        <x-icon name="o-clock" class="w-5 h-5" />
                                        <span>Durasi: {{ $result['rute']->durasi }}
                                            ({{ $result['rute']->jarak_tempuh }} km)
                                        </span>
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

    <style>
        /* Fix untuk x-choices agar tidak memanjang ke bawah */
        [x-choices] {
            max-height: 300px;
            overflow-y: auto;
        }

        /* Responsivitas tambahan */
        @media (max-width: 768px) {
            .md\:grid-cols-5 {
                grid-template-columns: 1fr;
            }

            .flex.items-center.justify-center {
                margin-top: 0.5rem;
            }
        }
    </style>
</div>

<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use App\Models\Kereta;

new #[Layout('components.layouts.app')] #[Title('Detail Kereta')] class extends Component {
    public $train;

    public function mount($id)
    {
        $this->train = Kereta::findOrFail($id);
    }
}; ?>

<div class="max-w-4xl mx-auto">
    <x-header title="Detail Kereta" subtitle="Informasi lengkap kereta (view only)" separator class="mb-6">
        <x-slot:actions>
            <x-button label="Kembali" link="/admin/manajemen-kereta" class="btn-primary btn-outline" />
        </x-slot:actions>
    </x-header>

    <x-card>
        <div class="">
            <div
                class="p-5 bg-base-200 transition-all duration-300 
               hover:ring-2 hover:ring-primary hover:shadow-lg hover:shadow-primary/20
               group relativen rounded-lg">
                <!-- Header dengan nama dan kode kereta -->
                <div class="mb-4">
                    <h2 class="card-title text-2xl">{{ $train->nama_kereta }}</h2>
                    <p class="text-gray-500">{{ $train->Kode_Kereta }}</p>
                </div>

                <!-- Grid untuk detail kereta -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm text-gray-600">ID Kereta</label>
                        <div class="mt-1 text-base-content">{{ $train->ID_Kereta }}</div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600">Kode Kereta</label>
                        <div class="mt-1 text-base-content">{{ $train->Kode_Kereta }}</div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600">Nama Kereta</label>
                        <div class="mt-1 text-base-content">{{ $train->nama_kereta }}</div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600">Tipe</label>
                        <div class="mt-1 text-base-content">{{ $train->tipe }}</div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600">Kapasitas</label>
                        <div class="mt-1 text-base-content">{{ $train->kapasitas }}</div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600">Status</label>
                        <div class="mt-1">
                            <x-badge :value="$train->status ?? '-'" :class="$train->status === 'Aktif' ? 'badge-success badge-soft' : 'badge-error badge-soft'" />
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-600">Keterangan Tambahan</label>
                        <div class="mt-1 text-base-content">{{ $train->keterangan ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Bagian gambar di bawah card body -->
            <div
                class="w-full mt-5 bg-base-200 transition-all duration-300 
               hover:ring-2 hover:ring-primary hover:shadow-lg hover:shadow-primary/20
               group relative overflow-hidden">
                @if ($train->foto_kereta)
                    <img src="{{ asset('storage/' . $train->foto_kereta) }}" alt="{{ $train->nama_kereta }}"
                        class="w-full h-auto rounded-lg object-cover shadow-md" />
                @else
                    <!-- fallback avatar -->
                    <div
                        class="transition-all duration-300 
               hover:ring-2 hover:ring-primary hover:shadow-lg hover:shadow-primary/20
               group relative overflow-hidden w-full h-56 rounded-lg bg-base-200 flex items-center justify-center text-gray-400 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V7M8 7V5a4 4 0 018 0v2" />
                        </svg>
                    </div>
                @endif
            </div>
        </div>
    </x-card>
</div>

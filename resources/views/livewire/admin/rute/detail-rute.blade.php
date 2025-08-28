<?php

use Livewire\Volt\Component;
use App\Models\Rute;
use App\Models\Stasiun;

new class extends Component {
    public bool $detailModal = false;
    public $route = null;

    protected $listeners = [
        'showDetailModal' => 'open',
    ];

    public function open($id)
    {
        $id = $id ?? $id;
        $this->route = Rute::with(['asal', 'tujuan'])->find($id);
        if (!$this->route) {
            $this->dispatch('toast', type: 'error', title: 'Error', message: 'Rute tidak ditemukan');
            return;
        }
        $this->detailModal = true;
    }

    public function close()
    {
        $this->detailModal = false;
        $this->route = null;
    }
}; ?>

<div>
    <x-modal wire:model="detailModal" title="Detail Rute" subtitle="Informasi lengkap rute" size="lg">
        @if ($this->route)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <x-input label="Kode Rute" :value="$this->route->Kode_Rute" readonly icon="o-key" />
                </div>

                <x-input label="Stasiun Asal" :value="$this->route->asal->nama_stasiun . ' (' . $this->route->asal->kota . ')'" readonly icon="o-map-pin" />

                <x-input label="Stasiun Tujuan" :value="$this->route->tujuan->nama_stasiun . ' (' . $this->route->tujuan->kota . ')'" readonly icon="o-flag" />

                <x-input label="Jarak Tempuh (km)" :value="$this->route->jarak_tempuh" readonly icon="o-arrows-pointing-out" />

                <x-input label="Durasi" :value="$this->route->durasi" readonly icon="o-clock" />

                <div class="md:col-span-2">
                    <x-textarea label="Jalur Rute" :value="$this->route->jalur_rute" readonly rows="4" icon="o-map" />
                </div>
            </div>
        @else
            <div class="p-4 text-sm text-gray-600">Tidak ada data untuk ditampilkan.</div>
        @endif

        <div class="mt-4 flex justify-end">
            <x-button label="Tutup" type="button" wire:click="close" class="btn-ghost" icon="o-x-mark" />
        </div>
    </x-modal>
</div>

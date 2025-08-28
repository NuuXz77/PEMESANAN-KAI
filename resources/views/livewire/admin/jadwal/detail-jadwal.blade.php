<?php

use App\Models\Jadwal;
use Livewire\Volt\Component;


new class extends Component {
    public bool $detailModal = false;
    public $jadwal = null;

    protected $listeners = [
        'showDetailJadwal' => 'open',
        'showDetailModal' => 'open',
    ];

    public function open($id)
    {
        $this->jadwal = Jadwal::with(['kereta', 'rute', 'rute.asal', 'rute.tujuan'])->find($id);
        if (!$this->jadwal) {
            return;
        }

        $this->detailModal = true;
    }

    public function close()
    {
        $this->detailModal = false;
        $this->jadwal = null;
    }
}; ?>

<div>
    <x-modal wire:model="detailModal" title="Detail Jadwal" subtitle="Informasi lengkap jadwal" size="md">
        @if($jadwal)
            <div class="space-y-2">
                <div><strong>Kode Jadwal:</strong> {{ $jadwal->Kode_Jadwal }}</div>
                <div><strong>Kereta:</strong> {{ $jadwal->kereta->nama_kereta ?? '-' }} ({{ $jadwal->kereta->Kode_Kereta ?? '' }})</div>
                <div><strong>Rute:</strong> {{ $jadwal->rute->asal->nama_stasiun ?? '' }} → {{ $jadwal->rute->tujuan->nama_stasiun ?? '' }}</div>
                <div><strong>Keberangkatan:</strong> {{ optional($jadwal->waktu_keberangkatan)->format('d M Y H:i') }}</div>
                <div><strong>Kedatangan:</strong> {{ optional($jadwal->waktu_kedatangan)->format('d M Y H:i') }}</div>
            </div>
        @else
            <p>Data tidak tersedia.</p>
        @endif
        <div class="mt-4 flex justify-end">
            <x-button label="Tutup" type="button" wire:click="close" class="btn-ghost" />
        </div>
    </x-modal>
</div>


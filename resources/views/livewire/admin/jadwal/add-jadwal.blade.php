<?php

use Livewire\Volt\Component;
use App\Models\Jadwal;
use App\Models\Kereta;
use App\Models\Rute;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $addModal = false;
    public array $state = [
        'Kode_Jadwal' => '',
        'id_kereta' => '',
        'id_rute' => '',
        'waktu_keberangkatan' => '',
        'waktu_kedatangan' => '',
    ];

    public $keretas = [];
    public $rutes = [];

    public function mount(): void
    {
        $this->keretas = Kereta::all()
            ->map(function ($k) {
                return ['id' => $k->ID_Kereta, 'name' => $k->nama_kereta . ' (' . $k->Kode_Kereta . ')'];
            })
            ->toArray();

        $this->rutes = Rute::with(['asal', 'tujuan'])->get()
            ->map(function ($r) {
                return ['id' => $r->ID_Rute, 'name' => $r->asal->nama_stasiun . ' → ' . $r->tujuan->nama_stasiun];
            })
            ->toArray();

        $this->generateKodeJadwal();
    }

    public function open(): void
    {
        $this->addModal = true;
    }

    public function close(): void
    {
        $this->addModal = false;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->state = [
            'Kode_Jadwal' => '',
            'id_kereta' => '',
            'id_rute' => '',
            'waktu_keberangkatan' => '',
            'waktu_kedatangan' => '',
        ];
        $this->generateKodeJadwal();
    }

    protected function rules(): array
    {
        return [
            'state.Kode_Jadwal' => 'required|string|unique:jadwals,Kode_Jadwal',
            'state.id_kereta' => 'required|exists:keretas,ID_Kereta',
            'state.id_rute' => 'required|exists:rutes,ID_Rute',
            'state.waktu_keberangkatan' => 'required|date',
            'state.waktu_kedatangan' => 'required|date',
        ];
    }

    public function generateKodeJadwal(): void
    {
        $this->state['Kode_Jadwal'] = 'JAD-' . now()->format('YmdHis');
    }

    public function save(): void
    {
        if (empty($this->state['Kode_Jadwal'])) {
            $this->generateKodeJadwal();
        }

        $validated = $this->validate();

        // ensure waktu_kedatangan not before keberangkatan
        if (strtotime($validated['state']['waktu_kedatangan']) < strtotime($validated['state']['waktu_keberangkatan'])) {
            $this->error('Waktu kedatangan harus sama atau setelah waktu keberangkatan.');
            return;
        }

        $j = new Jadwal();
        $j->Kode_Jadwal = $validated['state']['Kode_Jadwal'];
        $j->id_kereta = $validated['state']['id_kereta'];
        $j->id_rute = $validated['state']['id_rute'];
        $j->waktu_keberangkatan = $validated['state']['waktu_keberangkatan'];
        $j->waktu_kedatangan = $validated['state']['waktu_kedatangan'];
        $j->save();

        $this->success('Jadwal berhasil ditambahkan!');
        $this->dispatch('refresh');
        $this->close();
    }
}; ?>

<div>
    <x-modal wire:model="addModal" title="Tambah Jadwal" subtitle="Isi data jadwal perjalanan." size="lg" persistent>
        <form wire:submit.prevent="save" class="p-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <x-input label="Kode Jadwal" wire:model="state.Kode_Jadwal" readonly icon="o-key" />
                </div>

                <x-select label="Kereta" wire:model="state.id_kereta" :options="$keretas" option-value="id" option-label="name" placeholder="Pilih kereta" icon="o-truck" />

                <x-select label="Rute" wire:model="state.id_rute" :options="$rutes" option-value="id" option-label="name" placeholder="Pilih rute" icon="o-map" />

                <div>
                    <x-datetime label="Waktu Keberangkatan" wire:model="state.waktu_keberangkatan" icon="o-clock" type="datetime-local"/>
                </div>

                <div>
                    <x-datetime label="Waktu Kedatangan" wire:model="state.waktu_kedatangan" icon="o-clock" type="datetime-local"/>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-button label="Batal" type="button" wire:click="close" icon="o-x-mark" class="btn-ghost" />
                <x-button label="Simpan" type="submit" class="btn-primary" icon="o-check" />
            </div>
        </form>
    </x-modal>

    <x-button icon="o-plus" title="haha" class="btn-secondary btn-circle" wire:click="open" />
</div>

<?php

use Livewire\Volt\Component;
use App\Models\Jadwal;
use App\Models\Kereta;
use App\Models\Rute;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $editModal = false;
    public array $state = [];
    public $jadwalId = null;
    public $keretas = [];
    public $rutes = [];

    protected $listeners = [
        'showEditJadwal' => 'open',
        'showEditModal' => 'open',
    ];

    public function mount(): void
    {
        $this->keretas = Kereta::all()
            ->map(fn($k) => ['id' => $k->ID_Kereta, 'name' => $k->nama_kereta . ' (' . $k->Kode_Kereta . ')'])
            ->toArray();

        $this->rutes = Rute::with(['asal', 'tujuan'])->get()
            ->map(fn($r) => ['id' => $r->ID_Rute, 'name' => $r->asal->nama_stasiun . ' → ' . $r->tujuan->nama_stasiun])
            ->toArray();
    }

    public function open($id)
    {
        $this->jadwalId = $id;
        $j = Jadwal::find($this->jadwalId);
        if (!$j) {
            $this->error('Jadwal tidak ditemukan');
            return;
        }

        $this->state = [
            'Kode_Jadwal' => $j->Kode_Jadwal,
            'id_kereta' => $j->id_kereta,
            'id_rute' => $j->id_rute,
            'waktu_keberangkatan' => optional($j->waktu_keberangkatan)->format('Y-m-d H:i:s'),
            'waktu_kedatangan' => optional($j->waktu_kedatangan)->format('Y-m-d H:i:s'),
        ];

        $this->editModal = true;
    }

    public function close()
    {
        $this->editModal = false;
        $this->state = [];
        $this->jadwalId = null;
    }

    protected function rules(): array
    {
        return [
            'state.Kode_Jadwal' => ['required', 'string', Rule::unique('jadwals', 'Kode_Jadwal')->ignore($this->jadwalId, 'ID_Jadwal')],
            'state.id_kereta' => 'required|exists:keretas,ID_Kereta',
            'state.id_rute' => 'required|exists:rutes,ID_Rute',
            'state.waktu_keberangkatan' => 'required|date',
            'state.waktu_kedatangan' => 'required|date',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        if (strtotime($validated['state']['waktu_kedatangan']) < strtotime($validated['state']['waktu_keberangkatan'])) {
            $this->error('Waktu kedatangan harus sama atau setelah waktu keberangkatan.');
            return;
        }

        $j = Jadwal::find($this->jadwalId);
        if (!$j) {
            $this->error('Jadwal tidak ditemukan');
            $this->close();
            return;
        }

        $j->Kode_Jadwal = $validated['state']['Kode_Jadwal'];
        $j->id_kereta = $validated['state']['id_kereta'];
        $j->id_rute = $validated['state']['id_rute'];
        $j->waktu_keberangkatan = $validated['state']['waktu_keberangkatan'];
        $j->waktu_kedatangan = $validated['state']['waktu_kedatangan'];
        $j->save();

        $this->success('Jadwal berhasil diperbarui');
        $this->dispatch('refresh');
        $this->close();
    }
}; ?>

<div>
    <x-modal wire:model="editModal" title="Edit Jadwal" subtitle="Ubah data jadwal" size="lg" persistent>
        <form wire:submit.prevent="save" class="p-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <x-input label="Kode Jadwal" wire:model="state.Kode_Jadwal" icon="o-key" />
                </div>

                <x-select label="Kereta" wire:model="state.id_kereta" :options="$keretas" option-value="id" option-label="name" placeholder="Pilih kereta" icon="o-truck" />

                <x-select label="Rute" wire:model="state.id_rute" :options="$rutes" option-value="id" option-label="name" placeholder="Pilih rute" icon="o-map" />

                <div>
                    <x-datetime label="Waktu Keberangkatan" wire:model="state.waktu_keberangkatan" icon="o-clock" />
                </div>

                <div>
                    <x-datetime label="Waktu Kedatangan" wire:model="state.waktu_kedatangan" icon="o-clock" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-button label="Batal" type="button" wire:click="close" icon="o-x-mark" class="btn-ghost" />
                <x-button label="Simpan" type="submit" class="btn-primary" icon="o-check" />
            </div>
        </form>
    </x-modal>
</div>

<?php

use Livewire\Volt\Component;
use App\Models\Rute;
use App\Models\Stasiun;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $editModal = false;
    public array $state = [];
    public $routeId = null;
    public $stasiuns = [];

    protected $listeners = [
        'showEditModal' => 'open',
    ];

    public function mount(): void
    {
        $this->stasiuns = Stasiun::all()
            ->map(function ($s) {
                return ['id' => $s->ID_Stasiun, 'name' => $s->nama_stasiun . ' (' . $s->kota . ')'];
            })
            ->toArray();
    }

    public function open($id)
    {
        $this->routeId = $id ?? $id;
        $r = Rute::find($this->routeId);
        if (!$r) {
            $this->error('Rute tidak ditemukan');
            return;
        }

        $this->state = [
            'Kode_Rute' => $r->Kode_Rute,
            'asal_id' => $r->asal_id,
            'tujuan_id' => $r->tujuan_id,
            'jarak_tempuh' => $r->jarak_tempuh,
            'durasi' => $r->durasi,
            'jalur_rute' => $r->jalur_rute,
        ];

        $this->editModal = true;
    }

    public function close()
    {
        $this->editModal = false;
        $this->state = [];
        $this->routeId = null;
    }

    protected function rules(): array
    {
        return [
            'state.Kode_Rute' => ['required', 'string', Rule::unique('rutes', 'Kode_Rute')->ignore($this->routeId, 'ID_Rute')],
            'state.asal_id' => 'required|exists:stasiuns,ID_Stasiun',
            'state.tujuan_id' => ['required', 'exists:stasiuns,ID_Stasiun', Rule::notIn([$this->state['asal_id'] ?? null])],
            'state.jarak_tempuh' => 'required|numeric|min:0',
            'state.durasi' => 'required|min:0',
            'state.jalur_rute' => 'nullable|string|max:500',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        $r = Rute::find($this->routeId);
        if (!$r) {
            $this->error('Rute tidak ditemukan');
            $this->close();
            return;
        }

        $r->Kode_Rute = $validated['state']['Kode_Rute'];
        $r->asal_id = $validated['state']['asal_id'];
        $r->tujuan_id = $validated['state']['tujuan_id'];
        $r->jarak_tempuh = $validated['state']['jarak_tempuh'];
        $r->durasi = $validated['state']['durasi'];
        $r->jalur_rute = $validated['state']['jalur_rute'] ?? null;
        $r->save();

        $this->success('Rute berhasil diperbarui');
        $this->dispatch('refresh');
        $this->close();
    }
}; ?>

<div>
    <x-modal wire:model="editModal" title="Edit Rute" subtitle="Ubah data rute" size="lg" persistent>
        <form wire:submit.prevent="save" class="p-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kode Rute -->
                <div class="md:col-span-2">
                    <x-input label="Kode Rute" wire:model="state.Kode_Rute" icon="o-key" />
                </div>

                <!-- Stasiun Asal -->
                <x-select label="Stasiun Asal" wire:model="state.asal_id" :options="$stasiuns"
                    placeholder="Pilih stasiun asal" option-value="id" option-label="name" icon="o-map-pin" />

                <!-- Stasiun Tujuan -->
                <x-select label="Stasiun Tujuan" wire:model="state.tujuan_id" :options="$stasiuns"
                    placeholder="Pilih stasiun tujuan" option-value="id" option-label="name" icon="o-flag" />

                <!-- Jarak Tempuh -->
                <x-input label="Jarak Tempuh (km)" wire:model="state.jarak_tempuh" type="number" min="0"
                    step="0.01" icon="o-arrows-pointing-out" />

                <!-- Durasi -->
                <x-datetime label="Durasi Perjalanan (menit)" wire:model="state.durasi" icon="o-clock" type="time" />

                <!-- Jalur Rute -->
                <div class="md:col-span-2">
                    <x-textarea label="Jalur Rute (opsional)" wire:model="state.jalur_rute"
                        placeholder="Deskripsi jalur rute..." rows="3" icon="o-map" />
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <x-button label="Batal" type="button" wire:click="close" icon="o-x-mark" class="btn-ghost" />
                <x-button label="Simpan" type="submit" class="btn-primary" icon="o-check" />
            </div>
        </form>
    </x-modal>
</div>

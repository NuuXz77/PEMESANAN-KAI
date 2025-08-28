<?php

use Livewire\Volt\Component;
use App\Models\Rute;
use App\Models\Stasiun;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $addModal = false;
    public array $state = [
        'Kode_Rute' => '',
        'asal_id' => '',
        'tujuan_id' => '',
        'jarak_tempuh' => '',
        'durasi' => '',
        'jalur_rute' => '',
    ];

    // Daftar stasiun untuk dropdown
    public $stasiuns = [];

    public function mount(): void
    {
        $this->stasiuns = Stasiun::all()
            ->map(function ($stasiun) {
                return [
                    'id' => $stasiun->ID_Stasiun,
                    'name' => $stasiun->nama_stasiun . ' (' . $stasiun->kota . ')',
                ];
            })
            ->toArray();

        $this->state['Kode_Rute'] = '';
        $this->generateKodeRute();
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
            'Kode_Rute' => '',
            'asal_id' => '',
            'tujuan_id' => '',
            'jarak_tempuh' => '',
            'durasi' => '',
            'jalur_rute' => '',
        ];
    }

    protected function rules(): array
    {
        return [
            'state.Kode_Rute' => 'required|string|unique:rutes,Kode_Rute',
            'state.asal_id' => 'required|exists:stasiuns,ID_Stasiun',
            'state.tujuan_id' => ['required', 'exists:stasiuns,ID_Stasiun', Rule::notIn([$this->state['asal_id']])],
            'state.jarak_tempuh' => 'required|numeric|min:0',
            'state.durasi' => 'required|min:0',
            'state.jalur_rute' => 'nullable|string|max:500',
        ];
    }

    // Generate kode rute berdasarkan stasiun asal dan tujuan
    public function generateKodeRute(): void
    {
        $asalId = $this->state['asal_id'] ?? null;
        $tujuanId = $this->state['tujuan_id'] ?? null;

        if (!$asalId || !$tujuanId) {
            $this->state['Kode_Rute'] = '';
            return;
        }

        $asal = Stasiun::find($asalId);
        $tujuan = Stasiun::find($tujuanId);

        if (!$asal || !$tujuan) {
            $this->state['Kode_Rute'] = '';
            return;
        }

        // Ambil inisial dari nama kota asal dan tujuan
        $kodeAsal = substr(strtoupper(preg_replace('/[^A-Z]/', '', $asal->kota)), 0, 3);
        $kodeTujuan = substr(strtoupper(preg_replace('/[^A-Z]/', '', $tujuan->kota)), 0, 3);

        if (empty($kodeAsal)) {
            $kodeAsal = 'ASL';
        }
        if (empty($kodeTujuan)) {
            $kodeTujuan = 'TJH';
        }

        $baseCode = $kodeAsal . '-' . $kodeTujuan;

        // Cek apakah sudah ada rute dengan base code yang sama
        $existingCount = Rute::where('Kode_Rute', 'like', $baseCode . '%')->count();

        if ($existingCount > 0) {
            $this->state['Kode_Rute'] = $baseCode . '-' . ($existingCount + 1);
        } else {
            $this->state['Kode_Rute'] = $baseCode;
        }
    }

    public function save(): void
    {
        // Jika kode rute kosong, generate otomatis
        if (empty($this->state['Kode_Rute'])) {
            $this->generateKodeRute();
        }

        $validated = $this->validate();

        // Simpan data rute
        $rute = new Rute();
        $rute->Kode_Rute = $validated['state']['Kode_Rute'];
        $rute->asal_id = $validated['state']['asal_id'];
        $rute->tujuan_id = $validated['state']['tujuan_id'];
        $rute->jarak_tempuh = $validated['state']['jarak_tempuh'];
        $rute->durasi = $validated['state']['durasi'];
        $rute->jalur_rute = $validated['state']['jalur_rute'] ?? null;
        $rute->save();

        $this->success('Rute berhasil ditambahkan!');
        $this->dispatch('refresh');
        $this->close();
    }
}; ?>

<div>
    <x-modal wire:model="addModal" title="Tambah Rute" subtitle="Isi data rute kereta api." size="lg" persistent>
        <form wire:submit.prevent="save" class="p-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kode Rute (otomatis) -->
                <div class="md:col-span-2">
                    <x-input label="Kode Rute" wire:model="state.Kode_Rute" readonly icon="o-key" />
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

    <x-button icon="o-plus" class="btn-secondary btn-circle" wire:click="open" />
</div>

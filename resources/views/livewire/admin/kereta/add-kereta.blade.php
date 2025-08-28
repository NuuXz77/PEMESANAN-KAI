<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\kereta as Kereta;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use Illuminate\Support\Str;

new class extends Component {
    use WithFileUploads;
    use Toast;

    public bool $addModal = false;
    public $photo = null;
    public array $state = [
        'Kode_Kereta' => '',
        'nama_kereta' => '',
        'tipe' => '',
        'kapasitas' => '',
        'status' => 'Aktif',
        // 'keterangan' => '',
    ];

    protected function rules(): array
    {
        return [
            'state.Kode_Kereta' => 'required|string|unique:keretas,Kode_Kereta',
            'state.nama_kereta' => 'required|string|max:255',
            'state.tipe' => ['required', Rule::in(['Eksekutif', 'Bisnis', 'Ekonomi'])],
            'state.kapasitas' => 'required|integer|min:1',
            'state.status' => ['required', Rule::in(['Aktif', 'Nonaktif'])],
            // 'state.keterangan' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|max:2048',
        ];
    }

    public function mount(): void
    {
        $this->generateKodeKereta();
    }

    public function open(): void
    {
        $this->generateKodeKereta();
        $this->addModal = true;
    }

    public function close(): void
    {
        $this->addModal = false;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->reset(['photo']);
        $this->state = [
            'Kode_Kereta' => '',
            'nama_kereta' => '',
            'tipe' => '',
            'kapasitas' => '',
            'status' => 'Aktif',
            // 'keterangan' => '',
        ];
        $this->generateKodeKereta();
    }

    protected function generateKodeKereta(): void
    {
        $date = now()->format('Ymd');
        $prefix = 'KRT' . $date;

        $last = Kereta::where('Kode_Kereta', 'like', $prefix . '%')
            ->orderBy('Kode_Kereta', 'desc')
            ->first();

        if ($last && preg_match('/(\d{3})$/', $last->Kode_Kereta, $m)) {
            $num = intval($m[1]) + 1;
        } else {
            $num = 1;
        }

        $suffix = str_pad($num, 3, '0', STR_PAD_LEFT);
        $this->state['Kode_Kereta'] = $prefix . $suffix;
    }

    public function save(): void
    {
        if (empty($this->state['Kode_Kereta'])) {
            $this->generateKodeKereta();
        }

        $validated = $this->validate();

        $photoPath = null;
        if ($this->photo) {
            // Generate nama file berdasarkan nama kereta
            $namaKereta = Str::slug($validated['state']['nama_kereta']);
            $extension = $this->photo->getClientOriginalExtension();
            $filename = $namaKereta . '.' . $extension;
            
            // Simpan gambar dengan nama custom
            $photoPath = $this->photo->storeAs('kereta', $filename, 'public');
        }

        $train = new Kereta();
        $train->Kode_Kereta = $validated['state']['Kode_Kereta'];
        $train->nama_kereta = $validated['state']['nama_kereta'];
        $train->tipe = $validated['state']['tipe'];
        $train->kapasitas = $validated['state']['kapasitas'];
        $train->status = $validated['state']['status'];
        // $train->keterangan = $validated['state']['keterangan'] ?? null;
        if ($photoPath) {
            $train->foto_kereta = $photoPath;
        }
        $train->save();

        $this->success('Kereta berhasil ditambahkan!');
        $this->dispatch('refresh');
        $this->close();
    }
}; ?>

<div>
    <x-modal wire:model="addModal" title="Tambah Kereta"
        subtitle="Isi data kereta. Foto akan disimpan dan dapat dilihat di detail." size="lg">
        <x-form wire:submit="save" class="p-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Kode Kereta" wire:model="state.Kode_Kereta" readonly />

                <x-input label="Nama Kereta" wire:model="state.nama_kereta" />
                <x-select label="Tipe" wire:model="state.tipe" placeholder="Pilih Tipe" :options="[
                    ['id' => 'Eksekutif', 'name' => 'Eksekutif'],
                    ['id' => 'Bisnis', 'name' => 'Bisnis'],
                    ['id' => 'Ekonomi', 'name' => 'Ekonomi'],
                ]"
                    option-value="id" option-label="name" />
                <x-input label="Kapasitas" type="number" wire:model="state.kapasitas" />
                <x-select label="Status" wire:model="state.status" :options="[['id' => 'Aktif', 'name' => 'Aktif'], ['id' => 'Nonaktif', 'name' => 'Nonaktif']]" option-value="id"
                    option-label="name" />
                <div class="md:col-span-2">
                    <x-file label="Foto Kereta" wire:model="photo" accept="image/png,image/jpeg" />
                    @if ($photo)
                        <div class="mt-2">
                            <img src="{{ $photo->temporaryUrl() }}" class="h-40 rounded-lg object-cover" />
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4 flex justify-end gap-2">
                <x-button label="Batal" type="button" wire:click="close" />
                <x-button label="Simpan" type="submit" class="btn-primary" />
            </div>
        </x-form>
    </x-modal>

    <x-button icon="o-plus" class="btn-secondary btn-circle" wire:click="open" />
</div>
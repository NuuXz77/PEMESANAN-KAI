<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Kereta;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads, Toast;

    public bool $editModal = false;
    public $photo = null;
    public $currentPhoto = null;
    public array $state = [];
    public $keretaId;
    public $keretaNama;

    // Event listener untuk membuka modal
    protected $listeners = ['showEditModal' => 'openModal'];

    public function openModal($id)
    {
        $this->keretaId = $id;
        $this->loadKeretaData();
        $this->editModal = true;
    }

    protected function rules(): array
    {
        return [
            'state.Kode_Kereta' => 'required|string|unique:keretas,Kode_Kereta,' . $this->keretaId . ',ID_Kereta',
            'state.nama_kereta' => 'required|string|max:255',
            'state.tipe' => ['required', Rule::in(['Eksekutif', 'Bisnis', 'Ekonomi'])],
            'state.kapasitas' => 'required|integer|min:1',
            'state.status' => ['required', Rule::in(['Aktif', 'Nonaktif'])],
            'photo' => 'nullable|image|max:2048',
        ];
    }

    protected function loadKeretaData(): void
    {
        $kereta = Kereta::findOrFail($this->keretaId);

        $this->state = [
            'Kode_Kereta' => $kereta->Kode_Kereta,
            'nama_kereta' => $kereta->nama_kereta,
            'tipe' => $kereta->tipe,
            'kapasitas' => $kereta->kapasitas,
            'status' => $kereta->status,
        ];

        $this->currentPhoto = $kereta->foto_kereta ? 'storage/' . $kereta->foto_kereta : null;
        $this->keretaNama = $kereta->nama_kereta;
    }

    public function close(): void
    {
        $this->editModal = false;
        $this->reset(['photo', 'currentPhoto', 'state', 'keretaId', 'keretaNama']);
    }

    public function removePhoto(): void
    {
        $this->photo = null;
        $this->currentPhoto = null;
    }

    public function save(): void
    {
        $validated = $this->validate();

        $kereta = Kereta::findOrFail($this->keretaId);

        // Handle photo upload
        if ($this->photo) {
            // Generate nama file berdasarkan nama kereta
            $namaKereta = Str::slug($validated['state']['nama_kereta']);
            $extension = $this->photo->getClientOriginalExtension();
            $filename = $namaKereta . '.' . $extension;

            // Simpan gambar baru
            $photoPath = $this->photo->storeAs('kereta', $filename, 'public');

            // Hapus foto lama jika ada
            if ($kereta->foto_kereta) {
                Storage::disk('public')->delete($kereta->foto_kereta);
            }

            $kereta->foto_kereta = $photoPath;
        } elseif ($this->currentPhoto === null && $kereta->foto_kereta) {
            // Jika user menghapus foto
            Storage::disk('public')->delete($kereta->foto_kereta);
            $kereta->foto_kereta = null;
        }

        // Update data kereta
        $kereta->update([
            'Kode_Kereta' => $validated['state']['Kode_Kereta'],
            'nama_kereta' => $validated['state']['nama_kereta'],
            'tipe' => $validated['state']['tipe'],
            'kapasitas' => $validated['state']['kapasitas'],
            'status' => $validated['state']['status'],
        ]);

        $this->success('Data kereta berhasil diperbarui!');
        $this->dispatch('refresh');
        $this->close();
    }
}; ?>

<div>
    <x-modal wire:model="editModal" title="Edit Kereta - {{ $keretaNama }}" subtitle="Perbarui data kereta" size="lg"
        persistent>
        @if ($editModal)
            <x-form wire:submit="save" class="p-0">
                <div class="space-y-4">
                    <!-- Baris 1: Kode dan Nama Kereta -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input label="Kode Kereta" wire:model="state.Kode_Kereta" readonly class="input-bordered w-full"
                            icon="o-qr-code" />

                        <x-input label="Nama Kereta" wire:model="state.nama_kereta" class="input-bordered w-full"
                            icon="o-truck" placeholder="Masukkan nama kereta" />
                    </div>

                    <!-- Baris 2: Tipe dan Kapasitas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-select label="Tipe Kereta" wire:model="state.tipe" placeholder="Pilih Tipe" :options="[
                            ['id' => 'Eksekutif', 'name' => 'Eksekutif'],
                            ['id' => 'Bisnis', 'name' => 'Bisnis'],
                            ['id' => 'Ekonomi', 'name' => 'Ekonomi'],
                        ]"
                            option-value="id" option-label="name" class="select-bordered w-full" icon="o-tag" />

                        <x-input label="Kapasitas Penumpang" type="number" wire:model="state.kapasitas"
                            class="input-bordered w-full" icon="o-users" min="1" />
                    </div>

                    <!-- Baris 3: Status -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-select label="Status Operasional" wire:model="state.status" :options="[['id' => 'Aktif', 'name' => 'Aktif'], ['id' => 'Nonaktif', 'name' => 'Nonaktif']]"
                            option-value="id" option-label="name" class="select-bordered w-full" icon="o-power" />
                    </div>

                    <!-- Baris 4: Upload Foto -->
                    <div class="space-y-2">
                        <x-file label="Foto Kereta" wire:model="photo" accept="image/png,image/jpeg"
                            class="file-input-bordered w-full" helper="Format: JPG/PNG (Maks. 2MB)" />

                        @if ($photo)
                            <div class="mt-2 flex items-center gap-4 p-3 bg-base-200 rounded-lg">
                                <img src="{{ $photo->temporaryUrl() }}" class="h-24 w-24 rounded-lg object-cover border"
                                    alt="Preview foto kereta" />
                                <div class="flex-1">
                                    <p class="font-medium">{{ $photo->getClientOriginalName() }}</p>
                                    <p class="text-sm text-gray-500">{{ round($photo->getSize() / 1024) }} KB</p>
                                </div>
                                <x-button label="Hapus" icon="o-trash" wire:click="removePhoto"
                                    class="btn-ghost text-error" spinner />
                            </div>
                        @endif
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex flex-col md:flex-row justify-end gap-3 pt-6 border-t">
                        <x-button label="Batal" icon="o-x-mark" wire:click="close" class="btn-ghost" />
                        <x-button label="Simpan Perubahan" icon="o-check" type="submit" class="btn-primary" spinner />
                    </div>
                </div>
            </x-form>
        @endif
    </x-modal>
</div>

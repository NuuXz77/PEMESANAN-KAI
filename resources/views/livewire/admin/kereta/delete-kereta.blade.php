<?php

use Livewire\Volt\Component;
use App\Models\Kereta;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $deleteModal = false;
    public $keretaId;
    public $keretaNama;

    public $listeners = ['showDeleteModal' => 'openModal'];

    public function openModal($id)
    {
        $kereta = kereta::findOrFail($id);
        $this->keretaId = $kereta->ID_Kereta;
        $this->keretaNama = $kereta->nama_kereta;
        $this->deleteModal = true;
    }

    public function close(): void
    {
        $this->deleteModal = false;
        $this->reset(['keretaId', 'keretaNama']);
    }

    public function confirmDelete(): void
    {
        $kereta = Kereta::findOrFail($this->keretaId);

        // Hapus foto jika ada
        if ($kereta->foto_kereta) {
            Storage::disk('public')->delete($kereta->foto_kereta);
        }

        $kereta->delete();

        $this->success('Kereta berhasil dihapus!');
        $this->dispatch('refresh');
        $this->close();
    }
}; ?>

<div>
    <x-modal wire:model="deleteModal" title="Hapus Kereta" subtitle="Anda yakin ingin menghapus kereta ini?" separator
        persistent>
        <div class="p-4">
            <p>Kereta yang akan dihapus: <strong>{{ $keretaNama }}</strong></p>
            <p class="text-red-500 mt-2">Aksi ini tidak dapat dibatalkan!</p>
        </div>

        <div class="flex justify-end gap-2 p-4">
            <x-button label="Batal" wire:click="close" />
            <x-button label="Hapus" wire:click="confirmDelete" class="btn-error" />
        </div>
    </x-modal>
</div>

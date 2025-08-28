<?php

use Livewire\Volt\Component;
use App\Models\Stasiun;
use Mary\Traits\Toast;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use Toast;

    public bool $deleteModal = false;
    public $stasiunId;
    public $stasiunNama;

    protected $listeners = ['showDeleteModal' => 'openModal'];

    public function openModal($id)
    {
        $stasiun = Stasiun::findOrFail($id);
        $this->stasiunId = $stasiun->ID_Stasiun;
        $this->stasiunNama = $stasiun->nama_stasiun;
        $this->deleteModal = true;
    }

    public function close(): void
    {
        $this->deleteModal = false;
        $this->reset(['stasiunId', 'stasiunNama']);
    }

    public function confirmDelete(): void
    {
        $stasiun = Stasiun::findOrFail($this->stasiunId);

        if ($stasiun->foto_stasiun) {
            Storage::disk('public')->delete($stasiun->foto_stasiun);
        }

        $stasiun->delete();

        $this->success('Stasiun berhasil dihapus!');
        $this->dispatch('refresh');
        $this->close();
    }
}; ?>

<div>
    <x-modal wire:model="deleteModal" title="Hapus Stasiun" subtitle="Anda yakin ingin menghapus stasiun ini?" separator persistent>
        <div class="p-4">
            <p>Stasiun yang akan dihapus: <strong>{{ $stasiunNama }}</strong></p>
            <p class="text-red-500 mt-2">Aksi ini tidak dapat dibatalkan!</p>
        </div>

        <div class="flex justify-end gap-2 p-4">
            <x-button label="Batal" wire:click="close" />
            <x-button label="Hapus" wire:click="confirmDelete" class="btn-error" />
        </div>
    </x-modal>
</div>

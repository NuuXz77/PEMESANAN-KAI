<?php

use Livewire\Volt\Component;
use App\Models\Rute;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $deleteModal = false;
    public $routeId;

    protected $listeners = [
        'showDeleteModal' => 'open',
    ];

    public function open($id)
    {
        $this->routeId = $id ?? $id;
        $this->deleteModal = true;
    }

    public function close()
    {
        $this->deleteModal = false;
        $this->routeId = null;
    }

    public function confirm()
    {
        if (!$this->routeId) {
            $this->error('ID rute tidak valid');
            return;
        }

        $r = Rute::find($this->routeId);
        if (!$r) {
            $this->error('Rute tidak ditemukan');
            $this->close();
            return;
        }

        $r->delete();

        $this->success('Rute berhasil dihapus');
        $this->dispatch('refresh');
        $this->close();
    }
}; ?>

<div>
    <x-modal wire:model="deleteModal" title="Hapus Rute" subtitle="Konfirmasi penghapusan rute" size="sm" persistent>
        <div class="p-2">
            <p class="text-sm text-gray-700">Apakah Anda yakin ingin menghapus rute ini? Tindakan ini tidak dapat
                dibatalkan.</p>
        </div>

        <div class="mt-4 flex justify-end gap-2">
            <x-button label="Batal" type="button" wire:click="close" class="btn-ghost" icon="o-x-mark" />
            <x-button label="Hapus" type="button" wire:click="confirm" class="btn-error" icon="o-trash" />
        </div>
    </x-modal>
</div>

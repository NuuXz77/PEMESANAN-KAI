<?php
use Livewire\Volt\Component;
use App\Models\Jadwal;
use Mary\Traits\Toast;

new class extends Component {
    use Toast;

    public bool $deleteModal = false;
    public $jadwalId = null;

    protected $listeners = [
        'showDeleteJadwal' => 'open',
        'showDeleteModal' => 'open',
    ];

    public function open($id)
    {
        $this->jadwalId = $id;
        $this->deleteModal = true;
    }

    public function close()
    {
        $this->deleteModal = false;
        $this->jadwalId = null;
    }

    public function delete(): void
    {
        $j = Jadwal::find($this->jadwalId);
        if (!$j) {
            $this->error('Jadwal tidak ditemukan');
            $this->close();
            return;
        }

        $j->delete();
        $this->success('Jadwal berhasil dihapus');
        $this->dispatch('refresh');
        $this->close();
    }
}; ?>

<div>
    <x-modal wire:model="deleteModal" title="Hapus Jadwal" subtitle="Konfirmasi penghapusan jadwal" size="sm"
        persistent>
        <div class="p-4">
            <p>Apakah Anda yakin ingin menghapus jadwal ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="mt-4 flex justify-end gap-2">
                <x-button label="Batal" type="button" wire:click="close" class="btn-ghost" />
                <x-button label="Hapus" type="button" wire:click="delete" class="btn-danger" />
            </div>
        </div>
    </x-modal>
</div>

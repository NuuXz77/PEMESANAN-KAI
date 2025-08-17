<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Kereta;
use Illuminate\Pagination\LengthAwarePaginator;

new #[Layout('components.layouts.app')] #[Title('Manajemen Kereta')] class extends Component {
    use WithPagination;

    public $headerTrains = [['key' => 'ID_Kereta', 'label' => '#', 'class' => 'text-center', 'sortable' => false], ['key' => 'Kode_Kereta', 'label' => 'Kode Kereta', 'class' => 'w-40'], ['key' => 'nama_kereta', 'label' => 'Nama Kereta'], ['key' => 'tipe', 'label' => 'Tipe', 'class' => 'w-32'], ['key' => 'kapasitas', 'label' => 'Kapasitas', 'class' => 'w-24 text-right'], ['key' => 'status', 'label' => 'Status', 'class' => 'w-24'], ['key' => 'actions', 'label' => 'Aksi', 'class' => 'w-24', 'sortable' => false]];

    // Filters
    public string $search = '';
    public string $filterStatus = '';
    public string $filterType = '';

    // Pagination
    public int $perPage = 3;

    // Sorting
    public $sortBy = ['column' => 'ID_Kereta', 'direction' => 'asc'];

    // Listener untuk refresh
    protected $listeners = [
        'refresh' => 'refreshTable',
        'reset-filter' => 'resetFilter',
    ];

    public function refreshTable()
    {
        $this->resetPage();
    }

    public function resetFilter()
    {
        $this->reset(['search', 'filterStatus', 'filterType']);
        $this->resetPage();
    }

    // Method untuk sorting
    public function sort($column)
    {
        if ($this->sortBy['column'] == $column) {
            $this->sortBy['direction'] = $this->sortBy['direction'] == 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy['column'] = $column;
            $this->sortBy['direction'] = 'asc';
        }
    }

    // Method untuk hapus data
    public function delete($id)
    {
        Kereta::find($id)->delete();
        $this->dispatch('toast', type: 'success', title: 'Berhasil', message: 'Data kereta berhasil dihapus', position: 'toast-top toast-end', timeout: 3000);
        $this->dispatch('refresh');
    }

    public function getTrainsProperty()
    {
        return Kereta::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('Kode_Kereta', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_kereta', 'like', '%' . $this->search . '%')
                        ->orWhere('tipe', 'like', '%' . $this->search . '%')
                        ->orWhere('kapasitas', 'like', '%' . $this->search . '%')
                        ->orWhere('status', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterType, function ($query) {
                $query->where('tipe', $this->filterType);
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);
    }
}; ?>

<div class="space-y-6">
    <!-- 1. Header/Judul -->
    <x-header title="Manajemen Kereta" subtitle="Kelola data kereta api KAI" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-button label="Reset Filter" wire:click="resetFilter" class="btn-ghost btn-sm" />
        </x-slot:middle>
        <x-slot:actions>
            <x-input placeholder="Cari kereta..." wire:model.live.debounce.300ms="search" icon="o-magnifying-glass"
                class="input-sm" />
            <x-select wire:model.live="filterStatus" placeholder="Filter Status" :options="[
                ['id' => '', 'name' => 'Semua'],
                ['id' => 'Aktif', 'name' => 'Aktif'],
                ['id' => 'Nonaktif', 'name' => 'Nonaktif'],
            ]" icon="o-check-badge"
                class="select-sm" option-value="id" option-label="name" />

            <x-select wire:model.live="filterType" placeholder="Filter Tipe" :options="[
                ['id' => '', 'name' => 'Semua'],
                ['id' => 'Eksekutif', 'name' => 'Eksekutif'],
                ['id' => 'Bisnis', 'name' => 'Bisnis'],
                ['id' => 'Ekonomi', 'name' => 'Ekonomi'],
            ]" icon="o-tag"
                class="select-sm" option-value="id" option-label="name" />
            <x-button icon="o-plus" class="btn-secondary btn-circle" />
        </x-slot:actions>
    </x-header>

    <!-- 3. Tabel Kereta -->
    <x-table :headers="$headerTrains" :rows="$this->trains->items()" :sort-by="$sortBy" per-page="perPage" :per-page-values="[3, 5, 10]" @sort="sort">
        @scope('cell_ID_Kereta', $train)
            <div class="text-center">{{ $loop->iteration + ($this->trains->currentPage() - 1) * $this->trains->perPage() }}
            </div>
        @endscope

        @scope('cell_kapasitas', $train)
            <div class="text-right">{{ $train->kapasitas }}</div>
        @endscope

        @scope('cell_status', $train)
            <x-badge :value="$train->status" :class="$train->status === 'Aktif' ? 'badge-success badge-soft' : 'badge-error badge-soft'" />
        @endscope

        @scope('cell_actions', $train)
            <x-dropdown>
                <x-slot:trigger>
                    <x-button icon="m-ellipsis-vertical" class="btn-circle" />
                </x-slot:trigger>
                <x-menu-item title="Edit" icon="o-pencil"
                    wire:click="$dispatch('showEditModal', { id: '{{ $train->ID_Kereta }}' })" />

                <x-menu-item title="Hapus" icon="o-trash"
                    wire:click="$dispatch('showDeleteModal', { id: '{{ $train->ID_Kereta }}' })" class="text-red-500" />
            </x-dropdown>
        @endscope
    </x-table>

    <!-- Pagination -->
    <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
        <!-- Per Page Selector -->
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-600">Items per page:</span>
            <x-select wire:model.live="perPage" :options="[
                ['id' => 3, 'name' => '3'],
                ['id' => 5, 'name' => '5'],
                ['id' => 10, 'name' => '10'],
                ['id' => 15, 'name' => '15'],
            ]" class="select-sm w-20" option-value="id"
                option-label="name" />
        </div>

        <!-- Pagination Links -->
        <div class="flex-1">
            {{ $this->trains->links() }}
        </div>
    </div>
</div>

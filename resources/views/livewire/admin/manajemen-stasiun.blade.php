<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Stasiun;
use Illuminate\Pagination\LengthAwarePaginator;

new #[Layout('components.layouts.app')] #[Title('Manajemen Stasiun')] class extends Component {
    use WithPagination;

    public $headerStations = [
        ['key' => 'ID_Stasiun', 'label' => '#', 'class' => 'text-center', 'sortable' => false],
        ['key' => 'Kode_Stasiun', 'label' => 'Kode Stasiun', 'class' => 'w-40'],
        ['key' => 'nama_stasiun', 'label' => 'Nama Stasiun'],
        ['key' => 'kota', 'label' => 'Kota', 'class' => 'w-32'],
        ['key' => 'alamat', 'label' => 'Alamat'],
        ['key' => 'actions', 'label' => 'Aksi', 'class' => 'w-24', 'sortable' => false]
    ];

    // Filters
    public string $search = '';
    public string $filterCity = '';

    // Pagination
    public int $perPage = 3;

    // Sorting
    public $sortBy = ['column' => 'ID_Stasiun', 'direction' => 'asc'];

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
        $this->reset(['search', 'filterCity']);
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
        Stasiun::find($id)->delete();
        $this->dispatch('toast', type: 'success', title: 'Berhasil', message: 'Data stasiun berhasil dihapus', position: 'toast-top toast-end', timeout: 3000);
        $this->dispatch('refresh');
    }

    public function getStationsProperty()
    {
        return Stasiun::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('Kode_Stasiun', 'like', '%' . $this->search . '%')
                        ->orWhere('nama_stasiun', 'like', '%' . $this->search . '%')
                        ->orWhere('kota', 'like', '%' . $this->search . '%')
                        ->orWhere('alamat', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterCity, function ($query) {
                $query->where('kota', $this->filterCity);
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);
    }
}; ?>

<div class="space-y-6">
    <!-- 1. Header/Judul -->
    <x-header title="Manajemen Stasiun" subtitle="Kelola data stasiun KAI" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-button label="Reset Filter" wire:click="resetFilter" class="btn-ghost btn-sm" />
        </x-slot:middle>
        <x-slot:actions>
            <x-input placeholder="Cari stasiun..." wire:model.live.debounce.300ms="search" icon="o-magnifying-glass"
                class="input-sm" />
            <x-select wire:model.live="filterCity" placeholder="Filter Kota" :options="[
                ['id' => '', 'name' => 'Semua Kota'],
                ['id' => 'Jakarta', 'name' => 'Jakarta'],
                ['id' => 'Bandung', 'name' => 'Bandung'],
                ['id' => 'Surabaya', 'name' => 'Surabaya'],
                ['id' => 'Yogyakarta', 'name' => 'Yogyakarta'],
                ['id' => 'Semarang', 'name' => 'Semarang'],
            ]" icon="o-building-library"
                class="select-sm" option-value="id" option-label="name" />
            <x-button icon="o-plus" class="btn-secondary btn-circle" />
        </x-slot:actions>
    </x-header>

    <!-- 3. Tabel Stasiun -->
    <x-table :headers="$headerStations" :rows="$this->stations->items()" :sort-by="$sortBy" per-page="perPage" :per-page-values="[3, 5, 10]" @sort="sort">
        @scope('cell_ID_Stasiun', $station)
            <div class="text-center">{{ $loop->iteration + ($this->stations->currentPage() - 1) * $this->stations->perPage() }}
            </div>
        @endscope

        @scope('cell_actions', $station)
            <x-dropdown>
                <x-slot:trigger>
                    <x-button icon="m-ellipsis-vertical" class="btn-circle" />
                </x-slot:trigger>
                <x-menu-item title="Edit" icon="o-pencil"
                    wire:click="$dispatch('showEditModal', { id: '{{ $station->ID_Stasiun }}' })" />

                <x-menu-item title="Hapus" icon="o-trash"
                    wire:click="$dispatch('showDeleteModal', { id: '{{ $station->ID_Stasiun }}' })" class="text-red-500" />
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
            {{ $this->stations->links() }}
        </div>
    </div>
</div>
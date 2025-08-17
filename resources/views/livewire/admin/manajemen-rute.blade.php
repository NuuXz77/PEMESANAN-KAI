<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Rute;
use App\Models\Stasiun;
use Illuminate\Pagination\LengthAwarePaginator;

new #[Layout('components.layouts.app')] #[Title('Manajemen Rute')] class extends Component {
    use WithPagination;

    public $headerRoutes = [
        ['key' => 'ID_Rute', 'label' => '#', 'class' => 'text-center', 'sortable' => false],
        ['key' => 'Kode_Rute', 'label' => 'Kode Rute', 'class' => 'w-40'],
        ['key' => 'asal', 'label' => 'Stasiun Asal'],
        ['key' => 'tujuan', 'label' => 'Stasiun Tujuan'],
        ['key' => 'jarak_tempuh', 'label' => 'Jarak (km)', 'class' => 'w-24 text-right'],
        ['key' => 'durasi', 'label' => 'Durasi', 'class' => 'w-24'],
        ['key' => 'actions', 'label' => 'Aksi', 'class' => 'w-24', 'sortable' => false]
    ];

    // Filters
    public string $search = '';
    public string $filterAsal = '';
    public string $filterTujuan = '';

    // Pagination
    public int $perPage = 3;

    // Sorting
    public $sortBy = ['column' => 'ID_Rute', 'direction' => 'asc'];

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
        $this->reset(['search', 'filterAsal', 'filterTujuan']);
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
        Rute::find($id)->delete();
        $this->dispatch('toast', type: 'success', title: 'Berhasil', message: 'Data rute berhasil dihapus', position: 'toast-top toast-end', timeout: 3000);
        $this->dispatch('refresh');
    }

    public function getRoutesProperty()
    {
        return Rute::with(['asal', 'tujuan'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('Kode_Rute', 'like', '%' . $this->search . '%')
                        ->orWhere('jarak_tempuh', 'like', '%' . $this->search . '%')
                        ->orWhere('durasi', 'like', '%' . $this->search . '%')
                        ->orWhereHas('asal', function($q) {
                            $q->where('nama_stasiun', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('tujuan', function($q) {
                            $q->where('nama_stasiun', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->filterAsal, function ($query) {
                $query->where('asal_id', $this->filterAsal);
            })
            ->when($this->filterTujuan, function ($query) {
                $query->where('tujuan_id', $this->filterTujuan);
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);
    }

    public function getStationOptionsProperty()
    {
        return Stasiun::all()->map(function ($station) {
            return ['id' => $station->ID_Stasiun, 'name' => $station->nama_stasiun];
        })->toArray();
    }
}; ?>

<div class="space-y-6">
    <!-- 1. Header/Judul -->
    <x-header title="Manajemen Rute" subtitle="Kelola data rute perjalanan KAI" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-button label="Reset Filter" wire:click="resetFilter" class="btn-ghost btn-sm" />
        </x-slot:middle>
        <x-slot:actions>
            <x-input placeholder="Cari rute..." wire:model.live.debounce.300ms="search" icon="o-magnifying-glass"
                class="input-sm" />
            <x-select wire:model.live="filterAsal" placeholder="Filter Asal" :options="[
                ['id' => '', 'name' => 'Semua Asal'],
                ...$this->stationOptions
            ]" icon="o-map-pin"
                class="select-sm" option-value="id" option-label="name" />

            <x-select wire:model.live="filterTujuan" placeholder="Filter Tujuan" :options="[
                ['id' => '', 'name' => 'Semua Tujuan'],
                ...$this->stationOptions
            ]" icon="o-flag"
                class="select-sm" option-value="id" option-label="name" />
            <x-button icon="o-plus" class="btn-secondary btn-circle" />
        </x-slot:actions>
    </x-header>

    <!-- 3. Tabel Rute -->
    <x-table :headers="$headerRoutes" :rows="$this->routes->items()" :sort-by="$sortBy" per-page="perPage" :per-page-values="[3, 5, 10]" @sort="sort">
        @scope('cell_ID_Rute', $route)
            <div class="text-center">{{ $loop->iteration + ($this->routes->currentPage() - 1) * $this->routes->perPage() }}
            </div>
        @endscope

        @scope('cell_asal', $route)
            {{ $route->asal->nama_stasiun }}
        @endscope

        @scope('cell_tujuan', $route)
            {{ $route->tujuan->nama_stasiun }}
        @endscope

        @scope('cell_jarak_tempuh', $route)
            <div class="text-right">{{ $route->jarak_tempuh }} km</div>
        @endscope

        @scope('cell_durasi', $route)
            {{ $route->durasi }}
        @endscope

        @scope('cell_actions', $route)
            <x-dropdown>
                <x-slot:trigger>
                    <x-button icon="m-ellipsis-vertical" class="btn-circle" />
                </x-slot:trigger>
                <x-menu-item title="Edit" icon="o-pencil"
                    wire:click="$dispatch('showEditModal', { id: '{{ $route->ID_Rute }}' })" />

                <x-menu-item title="Hapus" icon="o-trash"
                    wire:click="$dispatch('showDeleteModal', { id: '{{ $route->ID_Rute }}' })" class="text-red-500" />
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
            {{ $this->routes->links() }}
        </div>
    </div>
</div>
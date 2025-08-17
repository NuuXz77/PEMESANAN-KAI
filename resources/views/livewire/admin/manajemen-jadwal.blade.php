<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\Jadwal;
use App\Models\Kereta;
use App\Models\Rute;
use Illuminate\Pagination\LengthAwarePaginator;

new #[Layout('components.layouts.app')] #[Title('Manajemen Jadwal')] class extends Component {
    use WithPagination;

    public $headerSchedules = [['key' => 'ID_Jadwal', 'label' => '#', 'class' => 'text-center', 'sortable' => false], ['key' => 'Kode_Jadwal', 'label' => 'Kode Jadwal', 'class' => 'w-40'], ['key' => 'kereta', 'label' => 'Kereta'], ['key' => 'rute', 'label' => 'Rute'], ['key' => 'waktu_keberangkatan', 'label' => 'Keberangkatan', 'class' => 'w-32'], ['key' => 'waktu_kedatangan', 'label' => 'Kedatangan', 'class' => 'w-32'], ['key' => 'actions', 'label' => 'Aksi', 'class' => 'w-24', 'sortable' => false]];

    // Filters
    public string $search = '';
    public string $filterKereta = '';
    public string $filterRute = '';

    // Pagination
    public int $perPage = 3;

    // Sorting
    public $sortBy = ['column' => 'ID_Jadwal', 'direction' => 'asc'];

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
        $this->reset(['search', 'filterKereta', 'filterRute']);
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
        Jadwal::find($id)->delete();
        $this->dispatch('toast', type: 'success', title: 'Berhasil', message: 'Data jadwal berhasil dihapus', position: 'toast-top toast-end', timeout: 3000);
        $this->dispatch('refresh');
    }

    public function getSchedulesProperty()
    {
        return Jadwal::with(['kereta', 'rute', 'rute.asal', 'rute.tujuan'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('Kode_Jadwal', 'like', '%' . $this->search . '%')
                        ->orWhere('waktu_keberangkatan', 'like', '%' . $this->search . '%')
                        ->orWhere('waktu_kedatangan', 'like', '%' . $this->search . '%')
                        ->orWhereHas('kereta', function ($q) {
                            $q->where('nama_kereta', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('rute', function ($q) {
                            $q->where('Kode_Rute', 'like', '%' . $this->search . '%')
                                ->orWhereHas('asal', function ($q) {
                                    $q->where('nama_stasiun', 'like', '%' . $this->search . '%');
                                })
                                ->orWhereHas('tujuan', function ($q) {
                                    $q->where('nama_stasiun', 'like', '%' . $this->search . '%');
                                });
                        });
                });
            })
            ->when($this->filterKereta, function ($query) {
                $query->where('id_kereta', $this->filterKereta);
            })
            ->when($this->filterRute, function ($query) {
                $query->where('id_rute', $this->filterRute);
            })
            ->orderBy($this->sortBy['column'], $this->sortBy['direction'])
            ->paginate($this->perPage);
    }

    public function getTrainOptionsProperty()
    {
        return Kereta::all()
            ->map(function ($train) {
                return ['id' => $train->ID_Kereta, 'name' => $train->nama_kereta];
            })
            ->toArray();
    }

    public function getRouteOptionsProperty()
    {
        return Rute::with(['asal', 'tujuan'])
            ->get()
            ->map(function ($route) {
                return [
                    'id' => $route->ID_Rute,
                    'name' => $route->asal->nama_stasiun . ' → ' . $route->tujuan->nama_stasiun,
                ];
            })
            ->toArray();
    }
}; ?>

<div class="space-y-6">
    <!-- 1. Header/Judul -->
    <x-header title="Manajemen Jadwal" subtitle="Kelola data jadwal perjalanan KAI" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-button label="Reset Filter" wire:click="resetFilter" class="btn-ghost btn-sm" />
        </x-slot:middle>
        <x-slot:actions>
            <x-input placeholder="Cari jadwal..." wire:model.live.debounce.300ms="search" icon="o-magnifying-glass"
                class="input-sm" />
            <x-select wire:model.live="filterKereta" placeholder="Filter Kereta" :options="[['id' => '', 'name' => 'Semua Kereta'], ...$this->trainOptions]" icon="o-user"
                class="select-sm" option-value="id" option-label="name" />

            <x-select wire:model.live="filterRute" placeholder="Filter Rute" :options="[['id' => '', 'name' => 'Semua Rute'], ...$this->routeOptions]" icon="o-map"
                class="select-sm" option-value="id" option-label="name" />
            <x-button icon="o-plus" class="btn-secondary btn-circle" />
        </x-slot:actions>
    </x-header>

    <!-- 3. Tabel Jadwal -->
    <x-table :headers="$headerSchedules" :rows="$this->schedules->items()" :sort-by="$sortBy" per-page="perPage" :per-page-values="[3, 5, 10]" @sort="sort">
        @scope('cell_ID_Jadwal', $schedule)
            <div class="text-center">
                {{ $loop->iteration + ($this->schedules->currentPage() - 1) * $this->schedules->perPage() }}
            </div>
        @endscope

        @scope('cell_kereta', $schedule)
            {{ $schedule->kereta->nama_kereta }} ({{ $schedule->kereta->Kode_Kereta }})
        @endscope

        @scope('cell_rute', $schedule)
            {{ $schedule->rute->asal->nama_stasiun }} → {{ $schedule->rute->tujuan->nama_stasiun }}
        @endscope

        @scope('cell_waktu_keberangkatan', $schedule)
            {{ $schedule->waktu_keberangkatan->format('d M Y H:i') }}
        @endscope

        @scope('cell_waktu_kedatangan', $schedule)
            {{ $schedule->waktu_kedatangan->format('d M Y H:i') }}
        @endscope

        @scope('cell_actions', $schedule)
            <x-dropdown>
                <x-slot:trigger>
                    <x-button icon="m-ellipsis-vertical" class="btn-circle" />
                </x-slot:trigger>
                <x-menu-item title="Edit" icon="o-pencil"
                    wire:click="$dispatch('showEditModal', { id: '{{ $schedule->ID_Jadwal }}' })" />

                <x-menu-item title="Hapus" icon="o-trash"
                    wire:click="$dispatch('showDeleteModal', { id: '{{ $schedule->ID_Jadwal }}' })" class="text-red-500" />
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
            {{ $this->schedules->links() }}
        </div>
    </div>
</div>

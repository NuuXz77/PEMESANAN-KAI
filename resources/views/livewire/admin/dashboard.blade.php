<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use App\Models\Transaction;
use App\Models\Train;

new #[Layout('components.layouts.app')] #[Title('Dashboard')] class extends Component {
    // Data statis untuk demo
    public $colors = [
        'completed' => 'badge-success',
        'pending' => 'badge-warning',
        'cancelled' => 'badge-error',
    ];
    public function stats(): array
    {
        return [
            'total_orders' => 1242,
            'revenue' => 185600000,
            'occupancy_rate' => 78,
            'pending_payments' => 12,
        ];
    }

    public array $salesChart = [
        'type' => 'bar',
        'data' => [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
            'datasets' => [
                [
                    'label' => 'Pendapatan (Juta Rp)',
                    'data' => [125, 190, 210, 180, 220, 250, 300],
                    'borderColor' => '#1e40af', // Warna biru KAI
                    'backgroundColor' => 'rgba(30, 64, 175, 0.1)',
                    'borderWidth' => 2,
                    'tension' => 0.4,
                ],
            ],
        ],
        'options' => [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah (Juta Rp)',
                    ],
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Bulan 2024',
                    ],
                ],
            ],
        ],
    ];

    // Data terakhir transaksi
    public function recentTransactions()
    {
        return [['id' => 'TRX-001', 'train' => 'Argo Wilis', 'date' => '2024-05-15', 'amount' => 450000, 'status' => 'completed'], ['id' => 'TRX-002', 'train' => 'Taksaka', 'date' => '2024-05-14', 'amount' => 320000, 'status' => 'completed'], ['id' => 'TRX-003', 'train' => 'Gajayana', 'date' => '2024-05-13', 'amount' => 280000, 'status' => 'pending'], ['id' => 'TRX-004', 'train' => 'Brawijaya', 'date' => '2024-05-12', 'amount' => 510000, 'status' => 'completed'], ['id' => 'TRX-005', 'train' => 'Sancaka', 'date' => '2024-05-11', 'amount' => 390000, 'status' => 'cancelled']];
    }
}; ?>

<div class="space-y-6">
    <!-- Header -->
    <x-header title="Dashboard Admin" subtitle="Ringkasan Sistem Pemesanan Tiket KAI">
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." icon="o-magnifying-glass" />
        </x-slot:middle>
    </x-header>

    <!-- Statistik Cards -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        <!-- Card 1 -->
        <x-stat
            class="bg-base-200 transition-all duration-300 
               hover:ring-2 hover:ring-primary hover:shadow-lg hover:shadow-primary/20
               group relative overflow-hidden"
            title="Total Pesanan" value="{{ number_format($this->stats()['total_orders']) }}" icon="o-ticket"
            description="+12% dari bulan lalu">

            <!-- Glow Effect -->
            <div
                class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 
                    transition-opacity duration-500">
            </div>
        </x-stat>

        <!-- Card 2 -->
        <x-stat
            class="bg-base-200 transition-all duration-300 
               hover:ring-2 hover:ring-primary hover:shadow-lg hover:shadow-primary/20
               group relative overflow-hidden"
            title="Pendapatan" value="Rp {{ number_format($this->stats()['revenue'], 0, ',', '.') }}" icon="o-banknotes"
            color="text-green-500">

            <div
                class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 
                    transition-opacity duration-500">
            </div>
        </x-stat>

        <!-- Card 3 -->
        <x-stat
            class="bg-base-200 transition-all duration-300 
               hover:ring-2 hover:ring-primary hover:shadow-lg hover:shadow-primary/20
               group relative overflow-hidden"
            title="Okupansi" value="{{ $this->stats()['occupancy_rate'] }}%" icon="o-user-group" color="text-blue-500">

            <div
                class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 
                    transition-opacity duration-500">
            </div>
        </x-stat>

        <!-- Card 4 -->
        <x-stat
            class="bg-base-200 transition-all duration-300 
               hover:ring-2 hover:ring-primary hover:shadow-lg hover:shadow-primary/20
               group relative overflow-hidden"
            title="Pembayaran Tertunda" value="{{ $this->stats()['pending_payments'] }}" icon="o-clock"
            color="text-orange-500">

            <div
                class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 
                    transition-opacity duration-500">
            </div>
        </x-stat>
    </div>

    <!-- Chart Section -->
    {{-- <x-card title="Statistik Bulanan" subtitle="Perkembangan penjualan tiket 2024" shadow separator> --}}
    <div class="relative group transition-all duration-300">

        <!-- Border Effect -->
        <div
            class="absolute -inset-0.5 rounded-lg bg-primary/50 opacity-0 
                group-hover:opacity-100 transition-opacity duration-300">
        </div>

        <!-- Glow Effect Background -->
        <div
            class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 
                    transition-opacity duration-500">
        </div>
        <!-- Chart Container (Original) -->
        <div class="relative bg-base-200 rounded-lg p-4 shadow-md">
            <x-chart wire:model="salesChart" />
        </div>
    </div>
    <!-- Recent Transactions -->
    <x-card title="Transaksi Terakhir" class="bg-base-200" separator shadow>
        <x-table :headers="[
            ['key' => 'id', 'label' => 'ID Transaksi'],
            ['key' => 'train', 'label' => 'Kereta'],
            ['key' => 'date', 'label' => 'Tanggal'],
            ['key' => 'amount', 'label' => 'Jumlah'],
            ['key' => 'status', 'label' => 'Status'],
        ]" :rows="$this->recentTransactions()">
            {{-- Custom status badge --}}
            @scope('cell_status', $row)
                {{-- <x-badge :value="$row['status']" class="{{ $colors[$row['status'] }} badge-xs" /> --}}
            @endscope

            {{-- Custom amount format --}}
            @scope('cell_amount', $row)
                Rp {{ number_format($row['amount'], 0, ',', '.') }}
            @endscope
        </x-table>

        <x-slot:actions>
            <x-button label="Lihat Semua Transaksi" icon="o-arrow-right" link="/admin/transactions"
                class="btn-primary" />
        </x-slot:actions>
    </x-card>
</div>

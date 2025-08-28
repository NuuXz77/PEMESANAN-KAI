<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" crossorigin="">
    <title>{{ $title ?? 'Beranda' }}</title>
</head>

<body class="min-h-screen h-screen font-sans antialiased text-base-content">
    <x-nav sticky full-width class="bg-base-200/20 backdrop-blur-md border border-neutral-600/20">

        <x-slot:brand>
            {{-- Drawer toggle for "main-drawer" --}}
            <label for="main-drawer" class="lg:hidden mr-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>

            {{-- Brand --}}
            <div><img src="{{ asset('img/logo.svg') }}" width="50" alt=""></div>
        </x-slot:brand>

        {{-- Right side actions --}}
        <x-slot:actions>
            <x-theme-toggle class="btn btn-circle" darkTheme="dark" lightTheme="light" />
            <x-button label="Messages" icon="o-envelope" link="###" class="btn-ghost btn-sm" responsive />
            <x-button label="Notifications" icon="o-bell" link="###" class="btn-ghost btn-sm" responsive />
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main with-nav full-width class="fixed">
        <div class="fixed h-full w-full dark:bg-neutral-900 bg-primary">
            <div class="fixed inset-0 bg-secondary bg-[size:20px_20px] opacity-20 blur-[100px]"></div>
        </div>
        {{-- SIDEBAR --}}
        @if ($user = auth()->user())
            <x-slot:sidebar drawer="main-drawer" collapsible collapse-text="Hide"
                class="bg-base-200/20 backdrop-blur-md border border-neutral-600/20">

                {{-- MENU --}}
                <x-menu activate-by-route>
                    {{-- User --}}
                    <x-menu-separator />

                    <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover
                        class="-mx-2 !-my-2 rounded">
                        <x-slot:actions>
                        </x-slot:actions>
                    </x-list-item>

                    <x-menu-separator />
                    @if ($user->isAdmin())
                        <!-- Dashboard -->
                        <x-menu-item title="Beranda" icon="o-home" link="/admin/dashboard" />

                        <!-- Data Master -->
                        <x-menu-sub title="Data Master" icon="o-circle-stack">
                            <x-menu-item title="Manajemen Kereta" icon="o-truck" link="/admin/manajemen-kereta" />
                            <x-menu-item title="Manajemen Stasiun" icon="o-building-library"
                                link="/admin/manajemen-stasiun" />
                            <x-menu-item title="Manajemen Rute" icon="o-map" link="/admin/manajemen-rute" />
                            <x-menu-item title="Manajemen Jadwal" icon="o-calendar" link="/admin/manajemen-jadwal" />
                        </x-menu-sub>

                        <!-- Transaksi -->
                        <x-menu-sub title="Transaksi" icon="o-credit-card">
                            <x-menu-item title="Semua Transaksi" icon="o-list-bullet" link="/admin/transactions" />
                            <x-menu-item title="Pembayaran Tertunda" icon="o-clock" link="/admin/pending-payments" />
                            <x-menu-item title="Pembatalan" icon="o-x-circle" link="/admin/cancellations" />
                        </x-menu-sub>

                        <!-- Laporan -->
                        <x-menu-sub title="Laporan" icon="o-chart-bar">
                            <x-menu-item title="Statistik Penjualan" icon="o-chart-bar" link="/admin/sales-report" />
                            <x-menu-item title="Okupansi Kereta" icon="o-user-group" link="/admin/occupancy-report" />
                            <x-menu-item title="Rekap Harian" icon="o-document-text" link="/admin/daily-summary" />
                        </x-menu-sub>

                        <!-- Manajemen Akun -->
                        <x-menu-sub title="Manajemen Akun" icon="o-users">
                            <x-menu-item title="Akun Admin" icon="o-shield-check" link="/admin/account/admin" />
                            <x-menu-item title="Akun Konsumen" icon="o-user" link="/admin/account/users" />
                            <x-menu-item title="Verifikasi NIK" icon="o-identification"
                                link="/admin/account/verifications" />
                            </x-menu-seb>

                            <x-menu-sub title="Pengaturan" icon="o-cog-6-tooth">
                                <x-menu-item title="Profile" icon="o-user" link="####" />
                                <livewire:auth.logout />
                            </x-menu-sub>
                            <!-- Pengaturan -->
                            <x-menu-item title="Pengaturan Sistem" icon="o-cog" link="/admin/settings" />

                            <!-- Divider -->
                            <x-menu-separator />

                            <!-- Quick Actions -->
                            <x-menu-sub title="Quick Actions" icon="o-bolt">
                                <x-menu-item title="Buat Jadwal Baru" icon="o-plus-circle"
                                    link="/admin/schedules/create" />
                                <x-menu-item title="Update Harga" icon="o-currency-dollar" link="/admin/pricing" />
                                <x-menu-item title="Broadcast Notifikasi" icon="o-megaphone"
                                    link="/admin/broadcast" />
                            </x-menu-sub>
                    @endif

                    @if ($user->isKonsumen())
                        <x-menu-item title="Beranda" icon="o-home" link="/dashboard" />
                        <x-menu-item title="Pesanan Saya" icon="o-ticket" link="#" />
                        <x-menu-item title="Rute Favorit" icon="o-star" link="#" />
                        <x-menu-item title="Informasi" icon="o-information-circle" link="#" />
                        <x-menu-item title="Kontak Kami" icon="o-phone" link="#" />
                        <x-menu-item title="Transaksi" icon="o-credit-card" link="#" />
                        <x-menu-sub title="Pengaturan" icon="o-cog-6-tooth">
                            <x-menu-item title="Profile" icon="o-user" link="/profile" />
                            <livewire:auth.logout />
                        </x-menu-sub>
                    @endif
                </x-menu>
            </x-slot:sidebar>
        @endif

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{-- Toast --}}
    <x-toast />
    @livewireScripts
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js" crossorigin=""></script>
</body>

</html>

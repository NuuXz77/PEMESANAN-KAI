<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use App\Models\Stasiun;

new #[Layout('components.layouts.app')] #[Title('Detail Stasiun')] class extends Component {
    public $station;

    public function mount($id)
    {
        $this->station = Stasiun::findOrFail($id);
    }
}; ?>

<div class="max-w-5xl mx-auto">
    <x-header title="Detail Stasiun" subtitle="Informasi lengkap stasiun (view only)" separator class="mb-6">
        <x-slot:actions>
            <x-button label="Kembali" link="/admin/manajemen-stasiun" class="btn-primary btn-outline" />
        </x-slot:actions>
    </x-header>

    <x-card>
        <!-- Top: details spanning full width -->
        <div>
            <div
                class="bg-base-200 transition-all duration-300 
               hover:ring-2 hover:ring-primary hover:shadow-lg hover:shadow-primary/20
               group relative overflow-hidden grid grid-cols-1 md:grid-cols-2 gap-4 p-5 rounded-lg">
                <div>
                    <label class="block text-sm text-gray-600">ID Stasiun</label>
                    <div class="mt-1 text-base-content">{{ $station->ID_Stasiun }}</div>
                </div>

                <div>
                    <label class="block text-sm text-gray-600">Kode Stasiun</label>
                    <div class="mt-1 text-base-content">{{ $station->Kode_Stasiun }}</div>
                </div>

                <div>
                    <label class="block text-sm text-gray-600">Nama Stasiun</label>
                    <div class="mt-1 text-base-content">{{ $station->nama_stasiun }}</div>
                </div>

                <div>
                    <label class="block text-sm text-gray-600">Kota</label>
                    <div class="mt-1 text-base-content">{{ $station->kota }}</div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm text-gray-600">Alamat</label>
                    <div class="mt-1 text-base-content">{{ $station->alamat ?? '-' }}</div>
                </div>

                <div>
                    <label class="block text-sm text-gray-600">Latitude</label>
                    <div class="mt-1 text-base-content">{{ $station->latitude ?? '-' }}</div>
                </div>

                <div>
                    <label class="block text-sm text-gray-600">Longitude</label>
                    <div class="mt-1 text-base-content">{{ $station->longitude ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Bottom: image (left) and map (right) on large screens, stacked on small -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
            <div class="order-1 lg:order-1">
                @if ($station->foto_stasiun)
                    <img src="{{ asset('storage/' . $station->foto_stasiun) }}" alt="{{ $station->nama_stasiun }}"
                        class="w-full h-64 lg:h-56 rounded-lg object-cover border bg-base-200 transition-all duration-300 
               hover:ring-2 hover:ring-primary hover:shadow-lg hover:shadow-primary/20
               group relative overflow-hidden" />
                @else
                    <div
                        class="transition-all duration-300 
               hover:ring-2 hover:ring-primary hover:shadow-lg hover:shadow-primary/20
               group relative overflow-hidden w-full h-64 lg:h-56 rounded-lg bg-base-200 flex items-center justify-center text-gray-400 border">
                        <span>Tidak ada foto</span>
                    </div>
                @endif
            </div>

            <div class="order-2 lg:order-2">
                @if ($station->latitude && $station->longitude)
                    <div id="map-{{ $station->ID_Stasiun }}"
                        class=" bg-base-200 transition-all duration-300 
               hover:ring-2 hover:ring-primary hover:shadow-lg hover:shadow-primary/20
               group relative overflow-hidden w-full h-64 lg:h-56 rounded-lg border">
                    </div>
                @else
                    <div
                        class="transition-all duration-300 
               hover:ring-2 hover:ring-primary hover:shadow-lg hover:shadow-primary/20
               group relative overflow-hidden w-full h-64 lg:h-56 rounded-lg bg-base-200 flex items-center justify-center text-gray-500 border">
                        Koordinat tidak tersedia
                    </div>
                @endif
            </div>
        </div>
    </x-card>
</div>

@if ($station->latitude && $station->longitude)
    <script>
        (function waitLeafletAndInit() {
            if (typeof window === 'undefined') return;
            const check = () => {
                if (typeof L === 'undefined') {
                    setTimeout(check, 50);
                    return;
                }
                initMap();
            };
            check();

            function initMap() {
                try {
                    const lat = parseFloat("{{ $station->latitude }}");
                    const lng = parseFloat("{{ $station->longitude }}");
                    const mapId = "map-{{ $station->ID_Stasiun }}";
                    const el = document.getElementById(mapId);
                    if (!el) return;

                    // Avoid double initialization
                    if (el.__leaflet_initialized) {
                        // Ensure correct view/marker
                        el.__leaflet_map.setView([lat, lng], 15);
                        if (el.__leaflet_marker) {
                            el.__leaflet_marker.setLatLng([lat, lng]);
                        } else {
                            el.__leaflet_marker = L.marker([lat, lng]).addTo(el.__leaflet_map);
                        }
                        return;
                    }

                    const map = L.map(mapId, {
                        zoomControl: true
                    }).setView([lat, lng], 15);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(map);

                    const marker = L.marker([lat, lng]).addTo(map)
                        .bindPopup(
                            `<b>{{ addslashes($station->nama_stasiun) }}</b><br>{{ addslashes($station->alamat ?? 'Alamat tidak tersedia') }}`
                        )
                        .openPopup();

                    // store references to prevent re-init and allow updates
                    el.__leaflet_initialized = true;
                    el.__leaflet_map = map;
                    el.__leaflet_marker = marker;

                    // fix sizing once visible
                    setTimeout(() => map.invalidateSize(), 200);
                } catch (e) {
                    console.error('Map init error', e);
                }
            }
        })();
    </script>
@endif

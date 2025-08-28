<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Stasiun;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

new class extends Component {
    use WithFileUploads, Toast;

    public bool $editModal = false;
    public ?int $stationId = null;
    public $photo = null;
    public array $state = [
        'Kode_Stasiun' => '',
        'nama_stasiun' => '',
        'kota' => '',
        'alamat' => '',
        'latitude' => '',
        'longitude' => '',
    ];

    protected $listeners = [
        'showEditModal' => 'openModal',
    ];

    protected function rules(): array
    {
        return [
            'state.Kode_Stasiun' => [
                'required',
                'string',
                Rule::unique('stasiuns', 'Kode_Stasiun')->ignore($this->stationId, 'ID_Stasiun'),
            ],
            'state.nama_stasiun' => 'required|string|max:255',
            'state.kota' => 'required|string|max:100',
            'state.alamat' => 'nullable|string|max:1000',
            'state.latitude' => 'nullable|numeric',
            'state.longitude' => 'nullable|numeric',
            'photo' => 'nullable|image|max:4096',
        ];
    }

    public function openModal($id)
    {
        $station = Stasiun::findOrFail($id);
        $this->stationId = $station->ID_Stasiun;
        $this->state = [
            'Kode_Stasiun' => $station->Kode_Stasiun,
            'nama_stasiun' => $station->nama_stasiun,
            'kota' => $station->kota,
            'alamat' => $station->alamat,
            'latitude' => $station->latitude,
            'longitude' => $station->longitude,
        ];
        $this->photo = null;
        $this->editModal = true;
        
        // Kirim data koordinat ke JavaScript
        $this->dispatch('initEditMap', [
            'lat' => $station->latitude,
            'lng' => $station->longitude
        ]);
    }

    public function close(): void
    {
        $this->editModal = false;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->reset(['photo']);
        $this->stationId = null;
        $this->state = [
            'Kode_Stasiun' => '',
            'nama_stasiun' => '',
            'kota' => '',
            'alamat' => '',
            'latitude' => '',
            'longitude' => '',
        ];
    }

    // Live generation of kode when editing name (optional)
    public function generateKodeStasiun(): void
    {
        $nama = trim((string)($this->state['nama_stasiun'] ?? ''));

        if (empty($nama)) {
            $this->state['Kode_Stasiun'] = '';
            return;
        }

        $clean = preg_replace('/[^A-Za-z0-9]/', '', strtoupper($nama));
        $base = 'ST' . substr($clean, 0, 8);

        // Find last code starting with base but ignore current record
        $query = Stasiun::where('Kode_Stasiun', 'like', $base . '%');
        if ($this->stationId) {
            $query->where('ID_Stasiun', '!=', $this->stationId);
        }
        $last = $query->orderBy('Kode_Stasiun', 'desc')->first();

        if (!$last) {
            $this->state['Kode_Stasiun'] = $base;
            return;
        }

        if (preg_match('/(\d{3})$/', $last->Kode_Stasiun, $m)) {
            $num = intval($m[1]) + 1;
        } else {
            $num = 1;
        }

        $suffix = str_pad($num, 3, '0', STR_PAD_LEFT);
        $this->state['Kode_Stasiun'] = $base . $suffix;
    }

    public function updatedStateKota($value)
    {
        $this->dispatch('searchEditLocation', kota: $value);
    }

    public function save(): void
    {
        if (empty($this->state['Kode_Stasiun'])) {
            $this->generateKodeStasiun();
        }

        $validated = $this->validate();

        $station = Stasiun::findOrFail($this->stationId);

        // handle photo replacement
        if ($this->photo) {
            // delete old photo
            if ($station->foto_stasiun) {
                Storage::disk('public')->delete($station->foto_stasiun);
            }
            $nama = Str::slug($validated['state']['nama_stasiun'] ?? 'stasiun');
            $extension = $this->photo->getClientOriginalExtension();
            $filename = $nama . '-' . time() . '.' . $extension;
            $photoPath = $this->photo->storeAs('stasiun', $filename, 'public');
            $station->foto_stasiun = $photoPath;
        }

        $station->Kode_Stasiun = $validated['state']['Kode_Stasiun'];
        $station->nama_stasiun = $validated['state']['nama_stasiun'];
        $station->kota = $validated['state']['kota'];
        $station->alamat = $validated['state']['alamat'] ?? null;
        $station->latitude = $validated['state']['latitude'] ?? null;
        $station->longitude = $validated['state']['longitude'] ?? null;
        $station->save();

        $this->success('Perubahan stasiun tersimpan!');
        $this->dispatch('refresh');
        $this->close();
    }
}; ?>

<div>
    <x-modal wire:model="editModal" title="Edit Stasiun" subtitle="Ubah data stasiun" 
        size="xl" @open="$dispatch('initEditMap')" persistent class="modal-wide">
        <form wire:submit.prevent="save" class="p-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Kode Stasiun" wire:model="state.Kode_Stasiun" readonly />
                <x-input label="Nama Stasiun" wire:model="state.nama_stasiun" 
                    x-on:input.debounce.500ms="$wire.generateKodeStasiun()" />
                <x-input label="Kota" wire:model="state.kota" 
                    x-on:change.debounce.500ms="$wire.dispatch('searchEditLocation', {kota: $event.target.value})" />
                <x-input label="Alamat" wire:model="state.alamat" />
                <x-input label="Latitude" wire:model="state.latitude" readonly />
                <x-input label="Longitude" wire:model="state.longitude" readonly />

                <div class="md:col-span-2">
                    <div id="edit-map-container" class="h-64 w-full rounded-lg border mb-4"></div>
                    <div class="text-xs text-gray-500 mt-1">
                        Klik pada peta untuk mengubah koordinat atau gunakan kotak pencarian di atas peta
                    </div>
                </div>

                <div class="md:col-span-2">
                    <x-file label="Ganti Foto Stasiun (opsional)" wire:model="photo" accept="image/png,image/jpeg" />
                    @if ($photo)
                        <div class="mt-2">
                            <img src="{{ $photo->temporaryUrl() }}" class="h-40 rounded-lg object-cover" />
                        </div>
                    @else
                        @php
                            $station = $stationId ? Stasiun::find($stationId) : null;
                            $fotoPath = $station && $station->foto_stasiun ? asset('storage/' . $station->foto_stasiun) : '';
                        @endphp
                        @if ($fotoPath)
                            <div class="mt-2">
                                <img src="{{ $fotoPath }}" class="h-40 rounded-lg object-cover" onerror="this.style.display='none'" />
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <div class="mt-4 flex justify-end gap-2">
                <x-button label="Batal" type="button" wire:click="close" />
                <x-button label="Simpan" type="submit" class="btn-primary" />
            </div>
        </form>
    </x-modal>

    <script>
        document.addEventListener('livewire:initialized', () => {
            let map, marker, geocoderControl;
            let mapInitialized = false;

            // Inisialisasi saat modal dibuka dengan koordinat yang sudah ada
            Livewire.on('initEditMap', (data) => {
                console.log('Initializing map with data:', data);
                if (!mapInitialized) {
                    setTimeout(() => initMap(data), 500);
                } else {
                    setTimeout(safeInvalidate, 500);
                }
            });

            // Event pencarian dari input "kota"
            Livewire.on('searchEditLocation', ({ kota }) => {
                if (!kota) return;
                console.log('Searching for location:', kota);
                if (!map) {
                    initMap();
                }
                nominatimSearch(kota).then(result => {
                    if (!result) {
                        console.warn('Location not found:', kota);
                        return;
                    }
                    console.log('Location found:', result);
                    const { lat, lon, name } = result;
                    applyLocation([lat, lon], name || kota, 13);
                });
            });

            function initMap(data = null) {
                // Pastikan elemen peta tersedia dan terlihat
                const mapContainer = document.getElementById('edit-map-container');
                if (!mapContainer || mapContainer.offsetParent === null) {
                    setTimeout(() => initMap(data), 100);
                    return;
                }

                // Hapus peta lama jika ada
                if (map) {
                    map.remove();
                    map = null;
                    marker = null;
                }

                // Tentukan view awal berdasarkan data yang ada atau default Indonesia
                let initialView = [-2.5489, 118.0149]; // Koordinat tengah Indonesia
                let initialZoom = 5;
                
                if (data && data.lat && data.lng && !isNaN(parseFloat(data.lat)) && !isNaN(parseFloat(data.lng))) {
                    initialView = [parseFloat(data.lat), parseFloat(data.lng)];
                    initialZoom = 13;
                    console.log('Setting initial view to:', initialView);
                }

                map = L.map('edit-map-container', {
                    zoomControl: true,
                    center: initialView,
                    zoom: initialZoom
                });

                // Tile OSM
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);

                // Kontrol zoom
                L.control.zoom({
                    position: 'topright'
                }).addTo(map);

                // Kontrol geocoder (search box dalam peta)
                geocoderControl = L.Control.geocoder({
                        defaultMarkGeocode: false,
                        collapsed: true,
                        placeholder: 'Cari lokasi di Indonesia...',
                        errorMessage: 'Lokasi tidak ditemukan.',
                        suggestMinLength: 2,
                        queryMinLength: 2,
                        showResultIcons: true,
                        geocoder: L.Control.Geocoder.nominatim({
                            geocodingQueryParams: {
                                countrycodes: 'id',
                                bounded: 1,
                                viewbox: '95.0,-11.0,141.0,6.0' // Bounding box Indonesia
                            }
                        })
                    })
                    .on('markgeocode', function(e) {
                        const center = e.geocode.center;
                        const name = e.geocode.name || 'Lokasi';
                        console.log('Geocoder result:', center, name);
                        applyLocation([center.lat, center.lng], name, 14);
                    })
                    .addTo(map);

                // Jika ada koordinat awal, tambahkan marker
                if (data && data.lat && data.lng && !isNaN(parseFloat(data.lat)) && !isNaN(parseFloat(data.lng))) {
                    const lat = parseFloat(data.lat);
                    const lng = parseFloat(data.lng);
                    
                    // Pastikan koordinat valid untuk Indonesia
                    if (lat >= -11 && lat <= 6 && lng >= 95 && lng <= 141) {
                        marker = L.marker([lat, lng]).addTo(map);
                        marker.bindPopup(
                            `<b>Lokasi Saat Ini</b><br>Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`
                        ).openPopup();
                        console.log('Marker added at:', [lat, lng]);
                    } else {
                        console.warn('Koordinat di luar Indonesia:', lat, lng);
                    }
                }

                // Event klik pada peta
                map.on('click', function(e) {
                    console.log('Map clicked at:', e.latlng);
                    handleMapClick(e.latlng);
                });

                // Invalidate size setelah peta siap
                map.whenReady(function() {
                    safeInvalidate();
                    mapInitialized = true;
                    console.log('Map initialized successfully');
                });
            }

            function handleMapClick(latlng) {
                // Set latitude & longitude ke Livewire - PERHATIAN: urutan [lat, lng]
                @this.set('state.latitude', latlng.lat);
                @this.set('state.longitude', latlng.lng);

                // Tambah atau update marker
                if (!marker) {
                    marker = L.marker(latlng).addTo(map);
                } else {
                    marker.setLatLng(latlng);
                }

                // Buka popup dengan koordinat
                marker.bindPopup(
                    `<b>Lokasi Dipilih</b><br>Lat: ${latlng.lat.toFixed(6)}, Lng: ${latlng.lng.toFixed(6)}`
                ).openPopup();
                
                console.log('Marker placed at:', latlng);
            }

            function applyLocation(latlngArr, label, zoom = 14) {
                const lat = parseFloat(latlngArr[0]);
                const lng = parseFloat(latlngArr[1]);
                
                console.log('Applying location:', lat, lng, label);

                // Validasi koordinat untuk Indonesia
                if (lat < -11 || lat > 6 || lng < 95 || lng > 141) {
                    console.warn('Koordinat di luar wilayah Indonesia:', lat, lng);
                    // Tetap terapkan tapi beri warning
                }

                // Sinkron ke Livewire - PERHATIAN: urutan [lat, lng]
                @this.set('state.latitude', lat);
                @this.set('state.longitude', lng);

                // Update peta
                map.setView([lat, lng], zoom);
                
                if (!marker) {
                    marker = L.marker([lat, lng]).addTo(map);
                } else {
                    marker.setLatLng([lat, lng]);
                }
                
                marker.bindPopup(`<b>${label}</b><br>Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`).openPopup();
                safeInvalidate();
            }

            async function nominatimSearch(q) {
                try {
                    console.log('Searching Nominatim for:', q);
                    // Prioritaskan pencarian di Indonesia
                    const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=5&addressdetails=0&countrycodes=id&bounded=1&viewbox=95.0,-11.0,141.0,6.0`;
                    
                    const res = await fetch(url, { 
                        headers: { 
                            'Accept': 'application/json',
                            'User-Agent': 'StasiunApp/1.0' 
                        } 
                    });
                    
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    
                    const data = await res.json();
                    
                    if (!Array.isArray(data) || data.length === 0) {
                        console.log('No results found in Indonesia, trying global search');
                        // Coba lagi tanpa filter Indonesia
                        const urlGlobal = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=1&addressdetails=0`;
                        const resGlobal = await fetch(urlGlobal, { 
                            headers: { 
                                'Accept': 'application/json',
                                'User-Agent': 'StasiunApp/1.0'
                            } 
                        });
                        
                        if (!resGlobal.ok) {
                            throw new Error(`HTTP error! status: ${resGlobal.status}`);
                        }
                        
                        const dataGlobal = await resGlobal.json();
                        if (!Array.isArray(dataGlobal) || dataGlobal.length === 0) {
                            console.log('No results found globally');
                            return null;
                        }
                        
                        return { 
                            lat: parseFloat(dataGlobal[0].lat), 
                            lon: parseFloat(dataGlobal[0].lon), 
                            name: dataGlobal[0].display_name 
                        };
                    }
                    
                    // Ambil hasil pertama
                    return { 
                        lat: parseFloat(data[0].lat), 
                        lon: parseFloat(data[0].lon), 
                        name: data[0].display_name 
                    };
                } catch (e) {
                    console.error('Nominatim error:', e);
                    return null;
                }
            }

            // Perbaikan untuk masalah peta hitam
            function safeInvalidate() {
                if (!map) return;

                // Tunggu hingga peta benar-benar terlihat
                if (document.getElementById('edit-map-container').offsetParent === null) {
                    setTimeout(safeInvalidate, 100);
                    return;
                }

                // Invalidate size dengan beberapa penanganan error
                try {
                    map.invalidateSize({
                        animate: false
                    });
                    console.log('Map invalidated size');

                    // Trigger resize event untuk Leaflet
                    setTimeout(() => {
                        window.dispatchEvent(new Event('resize'));
                    }, 100);
                } catch (e) {
                    console.error('Error in safeInvalidate:', e);
                }
            }

            // Tangani event saat modal ditutup
            Livewire.on('close', () => {
                console.log('Modal closed, cleaning up map');
                if (map) {
                    // Hapus peta saat modal ditutup untuk mencegah memori penuh
                    setTimeout(() => {
                        if (map) {
                            map.remove();
                            map = null;
                            marker = null;
                            mapInitialized = false;
                            console.log('Map cleaned up');
                        }
                    }, 500);
                }
            });
        });
    </script>

    <style>
        /* CSS untuk memperlebar modal */
        .modal-wide .modal-box {
            max-width: 90vw;
            width: 800px;
        }
        
        /* Memastikan peta terlihat dengan baik */
        #edit-map-container {
            min-height: 300px;
            z-index: 1;
        }
        
        /* Memperbaiki tampilan kontrol peta */
        .leaflet-control-geocoder {
            width: 300px;
            max-width: 90%;
        }
        
        .leaflet-control-geocoder-form input {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        
        /* Pastikan peta memiliki z-index yang tepat */
        .leaflet-container {
            z-index: 1;
            font-family: inherit;
        }
        
        /* Style untuk geocoder results */
        .leaflet-control-geocoder-alternatives {
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</div>
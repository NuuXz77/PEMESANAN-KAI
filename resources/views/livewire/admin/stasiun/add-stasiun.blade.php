<?php

use Livewire\Volt\Component;
use Livewire\WithFileUploads;
use App\Models\Stasiun;
use Illuminate\Validation\Rule;
use Mary\Traits\Toast;
use Illuminate\Support\Str;

new class extends Component {
    use WithFileUploads, Toast;

    public bool $addModal = false;
    public $photo = null;
    public array $state = [
        'Kode_Stasiun' => '',
        'nama_stasiun' => '',
        'kota' => '',
        'alamat' => '',
        'latitude' => '',
        'longitude' => '',
    ];

    protected function rules(): array
    {
        return [
            'state.Kode_Stasiun' => 'required|string|unique:stasiuns,Kode_Stasiun',
            'state.nama_stasiun' => 'required|string|max:255',
            'state.kota' => 'required|string|max:100',
            'state.alamat' => 'nullable|string|max:1000',
            'state.latitude' => 'nullable|numeric',
            'state.longitude' => 'nullable|numeric',
            'photo' => 'nullable|image|max:4096',
        ];
    }

    public function mount(): void
    {
        // jangan generate otomatis di mount supaya field kosong sampai user mengetik nama
    }

    public function open(): void
    {
        // jangan generate otomatis saat modal dibuka
        $this->addModal = true;
        $this->dispatch('initMap');
    }

    public function close(): void
    {
        $this->addModal = false;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->reset(['photo']);
        $this->state = [
            'Kode_Stasiun' => '',
            'nama_stasiun' => '',
            'kota' => '',
            'alamat' => '',
            'latitude' => '',
            'longitude' => '',
        ];
        // tidak regenerate di reset — akan ter-generate saat user mulai mengetik nama
    }

    // Generate kode stasiun secara live saat user mengetik nama stasiun
    public function generateKodeStasiun(): void
    {
        $nama = trim((string) ($this->state['nama_stasiun'] ?? ''));

        if (empty($nama)) {
            $this->state['Kode_Stasiun'] = '';
            return;
        }

        // Normalisasi: uppercase, hapus spasi & karakter non-alfanumerik
        $clean = preg_replace('/[^A-Za-z0-9]/', '', strtoupper($nama));

        // Batasi panjang base code
        $base = 'ST' . substr($clean, 0, 8);

        // Cek eksisting kode yang dimulai dengan base
        $last = Stasiun::where('Kode_Stasiun', 'like', $base . '%')
            ->orderBy('Kode_Stasiun', 'desc')
            ->first();

        if (!$last) {
            $this->state['Kode_Stasiun'] = $base;
            return;
        }

        // Jika ada, coba temukan suffix 3 digit terakhir
        if (preg_match('/(\d{3})$/', $last->Kode_Stasiun, $m)) {
            $num = intval($m[1]) + 1;
        } else {
            $num = 1;
        }

        $suffix = str_pad($num, 3, '0', STR_PAD_LEFT);
        $this->state['Kode_Stasiun'] = $base . $suffix;
    }

    // updatedStateKota tetap ada untuk fitur pencarian lokasi
    public function updatedStateKota($value)
    {
        $this->dispatch('searchLocation', kota: $value);
    }

    public function save(): void
    {
        // safety: jika kode kosong saat submit, generate ulang dari nama
        if (empty($this->state['Kode_Stasiun'])) {
            $this->generateKodeStasiun();
        }

        $validated = $this->validate();

        $photoPath = null;
        if ($this->photo) {
            $nama = Str::slug($validated['state']['nama_stasiun'] ?? 'stasiun');
            $extension = $this->photo->getClientOriginalExtension();
            $filename = $nama . '-' . time() . '.' . $extension;
            $photoPath = $this->photo->storeAs('stasiun', $filename, 'public');
        }

        $station = new Stasiun();
        $station->Kode_Stasiun = $validated['state']['Kode_Stasiun'];
        $station->nama_stasiun = $validated['state']['nama_stasiun'];
        $station->kota = $validated['state']['kota'];
        $station->alamat = $validated['state']['alamat'] ?? null;
        $station->latitude = $validated['state']['latitude'] ?? null;
        $station->longitude = $validated['state']['longitude'] ?? null;
        if ($photoPath) {
            $station->foto_stasiun = $photoPath;
        }
        $station->save();

        $this->success('Stasiun berhasil ditambahkan!');
        $this->dispatch('refresh');
        $this->close();
    }
}; ?>

<div>
    <x-modal wire:model="addModal" title="Tambah Stasiun" subtitle="Isi data stasiun. Foto dan koordinat akan disimpan."
        size="xl" @open="$dispatch('initMap')" persistent class="modal-wide">
        <form wire:submit.prevent="save" class="p-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Kode Stasiun" wire:model="state.Kode_Stasiun" readonly />

                <x-input label="Nama Stasiun" wire:model="state.nama_stasiun"
                    x-on:input.debounce.500ms="$wire.generateKodeStasiun()" />

                <x-input label="Kota" wire:model="state.kota"
                    x-on:change.debounce.500ms="$wire.dispatch('searchLocation', {kota: $event.target.value})" />
                <x-input label="Alamat" wire:model="state.alamat" />

                <x-input label="Latitude" wire:model="state.latitude" readonly />
                <x-input label="Longitude" wire:model="state.longitude" readonly />

                <div class="md:col-span-2">
                    <div id="map-container" class="h-64 w-full rounded-lg border mb-4"></div>
                </div>

                <div class="md:col-span-2">
                    <x-file label="Foto Stasiun" wire:model="photo" accept="image/png,image/jpeg" />
                    @if ($photo)
                        <div class="mt-2">
                            <img src="{{ $photo->temporaryUrl() }}" class="h-40 rounded-lg object-cover" />
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4 flex justify-end gap-2">
                <x-button label="Batal" type="button" wire:click="close" />
                <x-button label="Simpan" type="submit" class="btn-primary" />
            </div>
        </form>
    </x-modal>

    <x-button icon="o-plus" class="btn-secondary btn-circle" wire:click="open" />

    <script>
        document.addEventListener('livewire:initialized', () => {
            let map, marker, geocoderControl;
            let mapInitialized = false;

            // Inisialisasi saat modal dibuka
            Livewire.on('initMap', () => {
                // Pastikan modal sudah terbuka sepenuhnya sebelum inisialisasi peta
                if (!mapInitialized) {
                    setTimeout(initMap, 500); // Ditambah delay untuk memastikan modal terbuka
                } else {
                    // Jika peta sudah diinisialisasi, cukup perbarui ukuran
                    setTimeout(safeInvalidate, 500);
                }
            });

            // Event pencarian dari input "kota"
            Livewire.on('searchLocation', ({
                kota
            }) => {
                if (!kota) return;
                if (!map) {
                    initMap();
                }
                nominatimSearch(kota).then(result => {
                    if (!result) return;
                    const {
                        lat,
                        lon,
                        name
                    } = result;
                    applyLocation([lat, lon], name || kota, 13);
                });
            });

            function initMap() {
                // Pastikan elemen peta tersedia dan terlihat
                const mapContainer = document.getElementById('map-container');
                if (!mapContainer || mapContainer.offsetParent === null) {
                    setTimeout(initMap, 100);
                    return;
                }

                // Hapus peta lama jika ada
                if (map) {
                    map.remove();
                }

                map = L.map('map-container', {
                    zoomControl: true
                }).setView([-2.5489, 118.0149], 5);

                // Tile OSM
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(map);

                // Kontrol geocoder (search box dalam peta)
                geocoderControl = L.Control.geocoder({
                        defaultMarkGeocode: false,
                        collapsed: false,
                        placeholder: 'Cari lokasi...',
                        errorMessage: 'Lokasi tidak ditemukan.'
                    })
                    .on('markgeocode', function(e) {
                        const center = e.geocode.center;
                        const name = e.geocode.name || 'Lokasi';
                        applyLocation([center.lat, center.lng], name, 14);
                    })
                    .addTo(map);

                // Event klik pada peta
                map.on('click', function(e) {
                    handleMapClick(e.latlng);
                });

                // Invalidate size setelah peta siap
                map.whenReady(function() {
                    safeInvalidate();
                    mapInitialized = true;
                });
            }

            function handleMapClick(latlng) {
                // Set latitude & longitude ke Livewire
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
            }

            function setMarker(latlng, html) {
                if (marker) {
                    marker.setLatLng(latlng);
                    if (html) marker.bindPopup(html).openPopup();
                } else {
                    marker = L.marker(latlng).addTo(map);
                    if (html) marker.bindPopup(html).openPopup();
                }
            }

            function applyLocation(latlngArr, label, zoom = 14) {
                const lat = parseFloat(latlngArr[0]),
                    lng = parseFloat(latlngArr[1]);

                // Sinkron ke Livewire
                @this.set('state.latitude', lat);
                @this.set('state.longitude', lng);

                // Update peta
                map.setView([lat, lng], zoom);
                setMarker([lat, lng], `<b>${label}</b><br>Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`);
                safeInvalidate();
            }

            async function nominatimSearch(q) {
                try {
                    const url =
                        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(q)}&limit=1&addressdetails=0`;
                    const res = await fetch(url, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json();
                    if (!Array.isArray(data) || data.length === 0) return null;
                    return {
                        lat: parseFloat(data[0].lat),
                        lon: parseFloat(data[0].lon),
                        name: data[0].display_name
                    };
                } catch (e) {
                    console.error('Nominatim error', e);
                    return null;
                }
            }

            // Perbaikan untuk masalah peta hitam
            function safeInvalidate() {
                if (!map) return;

                // Tunggu hingga peta benar-benar terlihat
                if (document.getElementById('map-container').offsetParent === null) {
                    setTimeout(safeInvalidate, 100);
                    return;
                }

                // Invalidate size dengan beberapa penanganan error
                try {
                    map.invalidateSize({
                        animate: false
                    });

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
                if (map) {
                    // Hapus peta saat modal ditutup untuk mencegah memori penuh
                    setTimeout(() => {
                        if (map) {
                            map.remove();
                            map = null;
                            marker = null;
                            mapInitialized = false;
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
        #map-container {
            min-height: 300px;
        }
        
        /* Memperbaiki tampilan kontrol peta */
        .leaflet-control-geocoder {
            width: 300px;
            max-width: 90%;
        }
        
        .leaflet-control-geocoder-form input {
            width: 100%;
        }
    </style>
</div>
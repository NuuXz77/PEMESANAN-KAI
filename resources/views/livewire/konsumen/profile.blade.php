<?php

use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use App\Models\User;
use Carbon\Carbon;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

new #[Layout('components.layouts.app')] #[Title('Profile')] class extends Component {
    use WithFileUploads;

    public $user;
    public $editMode = false;
    public $photo;
    public $state = [
        'nama' => '',
        'email' => '',
        'nik' => '',
        'nomor_telp' => '',
        'password' => '', // Changed from new_password
        'password_confirmation' => '',
    ];

    public function mount(): void
    {
        $this->user = Auth::user();
        $this->state = [
            'nama' => $this->user->nama,
            'email' => $this->user->email,
            'nik' => $this->user->nik,
            'nomor_telp' => $this->user->nomor_telp,
        ];
    }

    public function toggleEdit(): void
    {
        $this->editMode = !$this->editMode;
        // Reset password fields when toggling edit mode
        $this->state['password'] = '';
        $this->state['password_confirmation'] = '';
    }

    public function save(): void
    {
        $rules = [
            'state.nama' => 'required|string|max:255',
            'state.email' => 'required|email|unique:users,email,' . $this->user->ID_Akun . ',ID_Akun',
            'state.nik' => 'required|numeric',
            'state.nomor_telp' => 'required|string|max:20',
            'state.password' => 'nullable|min:8|confirmed',
            'photo' => 'nullable|image|max:2048',
        ];

        $validated = $this->validate($rules);

        $updateData = [
            'nama' => $validated['state']['nama'],
            'email' => $validated['state']['email'],
            'nik' => $validated['state']['nik'],
            'nomor_telp' => $validated['state']['nomor_telp'],
        ];

        if ($this->photo) {
            $path = $this->photo->store('profile-photos', 'public');
            $this->user->update(['foto_profile' => $path]);
        }

        if (!empty($validated['state']['password'])) {
            $updateData['password'] = Hash::make($validated['state']['password']);
        }

        $this->user->update($updateData);

        // Clear password fields after save
        $this->state['password'] = '';
        $this->state['password_confirmation'] = '';

        $this->editMode = false;
        $this->dispatch('profile-updated');
    }
}; ?>

<div>
    <x-header title="Profile" subtitle="Kelola informasi profil Anda" separator class="mb-6">
        @if (!$editMode)
            <x-slot:actions>
                <x-button icon="o-pencil-square" label="Edit Profile" @click="$wire.toggleEdit()"
                    class="btn-primary btn-soft" />
            </x-slot:actions>
        @endif
    </x-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Photo -->
        <div class="lg:col-span-1">
            <x-card>
                <div class="flex flex-col items-center">
                    @if ($editMode)
                        <x-file wire:model="photo" accept="image/png, image/jpeg">
                            <img src="{{ $user->foto_profile ? asset('storage/' . $user->foto_profile) : null }}"
                                class="h-40 rounded-lg" />
                        </x-file>
                        {{-- <x-avatar image="{{ $user->foto_profile ? asset('storage/' . $user->foto_profile) : null }}"
                            class="!w-32 !h-32 mb-4" />
                        <x-file wire:model="photo" accept="image/png, image/jpeg" label="Ubah Foto Profil" /> --}}
                    @else
                        <x-avatar image="{{ $user->foto_profile ? asset('storage/' . $user->foto_profile) : null }}"
                            class="!w-32 !h-32" />
                        <h3 class="mt-4 text-xl font-semibold">{{ $user->nama }}</h3>
                        <p class="text-gray-500">{{ ucfirst($user->role) }}</p>
                    @endif
                </div>
            </x-card>

            @if (!$editMode)
                <x-card class="mt-6">
                    <div class="flex items-center justify-between">
                        <x-header title="Status Akun" class="!mb-0" />
                        <x-badge :value="$user->status" @class([
                            'badge-success badge-soft' => $user->status === 'aktif',
                            'badge-error badge-soft' => $user->status === 'nonaktif',
                        ]) />
                    </div>
                </x-card>
            @endif
        </div>

        <!-- Profile Info -->
        <div class="lg:col-span-2">
            <x-card>
                @if ($editMode)
                    <x-form wire:submit="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <x-input label="Nama Lengkap" wire:model="state.nama" />
                            <x-input label="Email" wire:model="state.email" type="email" />
                            <x-input label="NIK" maxlength=16 wire:model="state.nik" type="tel" />
                            <x-input label="Nomor Telepon" maxlength=14 wire:model="state.nomor_telp" type="tel" />

                            <!-- Password Change Section -->
                            <div class="md:col-span-2">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                    <x-password label="Password Baru" wire:model="state.password"
                                        placeholder="Minimal 8 karakter" hint="Jika ingin mengubah password" />
                                    <x-password label="Konfirmasi Password" wire:model="state.password_confirmation"
                                        placeholder="Harus sama dengan password" />
                                </div>
                                @error('state.password')
                                    <span class="text-error text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <x-slot:actions>
                            <x-button label="Batal" @click="$wire.toggleEdit()" />
                            <x-button label="Simpan" type="submit" class="btn-primary" spinner="save" />
                        </x-slot:actions>
                    </x-form>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <x-input label="Kode Akun" :value="$user->Kode_Akun" disabled />
                        <x-input label="Nama Lengkap" :value="$user->nama" disabled />
                        <x-input label="Email" :value="$user->email" disabled />
                        <x-input label="NIK" :value="$user->nik ?? '-'" disabled />
                        <x-input label="Nomor Telepon" :value="$user->nomor_telp ?? '-'" disabled />
                        <x-input label="Role" :value="ucfirst($user->role)" disabled />
                        <x-input label="Tanggal Dibuat" :value="$user->tanggal_dibuat->format('d M Y')" disabled />
                    </div>
                @endif
            </x-card>
        </div>
    </div>

    <x-toast />
</div>

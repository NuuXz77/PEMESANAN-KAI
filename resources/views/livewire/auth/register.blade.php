<?php
use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

new #[Layout('components.layouts.auth')] #[Title('Register')] class extends Component {
    public $nama = '';
    public $email = '';
    public $password = '';
    public $nik = '';
    public $nomor_telp = '';
    public $setuju = false;

    public function register()
    {
        $this->validate([
            'nama' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'nik' => 'required|numeric',
            'nomor_telp' => 'required|numeric',
            'setuju' => 'accepted',
        ]);

        $count = User::count() + 1;
        $date = now()->format('Ymd');
        $kode_akun = 'USRKAI' . $date . str_pad($count, 5, '0', STR_PAD_LEFT);

        $user = User::create([
            'Kode_Akun' => $kode_akun,
            'nama' => $this->nama,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'nik' => $this->nik,
            'nomor_telp' => $this->nomor_telp,
            'status' => 'aktif',
            'role' => 'konsumen',
            'tanggal_dibuat' => now()->toDateString(),
        ]);

        auth()->login($user);

        return redirect('/dashboard');
    }
};
?>

<div class="w-full h-11/12 max-w-7xl mx-auto">
    <!-- Main Content -->
    <div class="w-auto relative overflow-hidden ">
        <!-- Form Container -->
        <div
            class="bg-neutral-600/20 dark:bg-neutral-400/20 backdrop-blur-[3px] border border-neutral-600/20 dark:border-neutral-400/20 rounded-2xl shadow-sm max-w-xl mx-auto">
            <div class="p-5 sm:p-6">
                <!-- Thema and back button -->
                <div class="flex justify-between items-center">
                    <a href="/login" class="inline-flex items-center">
                        <x-icon name="c-arrow-left" label="Kembali" class="mr-1" />
                    </a>
                    <x-theme-toggle class="btn btn-circle" darkTheme="dark" lightTheme="light" class="mr-5" />
                </div>

                <!-- Logo & Title -->
                <div class="text-center">
                    <img src="{{ asset('img/logo.svg') }}" alt="Logo" width="60" class="mx-auto mb-2">
                    <h1 class="text-xl font-bold">Sign up</h1>
                    <p class="mt-1 text-sm">
                        Sudah memiliki akun ?
                        <a href="/login" class="text-blue-600 hover:underline font-medium dark:text-blue-400">
                            Login
                        </a>
                    </p>
                </div>

                <!-- Form -->
                <x-form wire:submit.prevent="register">
                    <div class="grid gap-y-3 md:grid-cols-2 md:gap-x-4">
                        <div class="md:col-span-2">
                            <x-input label="Nama" wire:model="nama" placeholder="Nama lengkap" icon="o-user"
                                hint="Masukkan nama lengkap Anda" />
                        </div>
                        <div>
                            <x-input label="Email" wire:model="email" placeholder="Alamat email" icon="c-academic-cap"
                                type="email" hint="Masukkan email aktif" />
                        </div>
                        <div>
                            <x-password label="Password" hint="Buat password" wire:model="password" clearable placeholder="Masukan password"/>
                        </div>
                        <div>
                            <x-input label="NIK" wire:model="nik" placeholder="Nomor Induk Kependudukan"
                                icon="c-academic-cap" type="number" hint="Masukkan NIK Anda" />
                        </div>
                        <div>
                            <x-input label="Nomor Telepon" wire:model="nomor_telp" placeholder="Nomor telepon"
                                icon="o-phone" type="number" hint="Masukkan nomor telepon aktif" />
                        </div>
                        <div class="md:col-span-2 flex items-center justify-between text-sm">
                            <x-checkbox label="Saya Setuju dengan Syarat & Ketentuan" wire:model="setuju" />
                        </div>
                    </div>
                    <x-button label="Daftar" type="submit" class="btn-primary text-base-100 dark:text-white" />
                </x-form>

                <!-- Divider -->
                <div
                    class="py-3 flex items-center text-xs text-gray-400 uppercase before:flex-1 before:border-t before:border-gray-200 before:me-4 after:flex-1 after:border-t after:border-gray-200 after:ms-4 dark:text-neutral-500 dark:before:border-neutral-600 dark:after:border-neutral-600">
                    Or
                </div>

                <!-- Google Sign-in -->
                <div class="mt-4">
                    <x-button class="w-full btn-primary btn-outline py-2 text-sm">
                        <svg class="w-4 h-auto" viewBox="0 0 46 47" fill="none">
                            <path
                                d="M46 24.0287C46 22.09 45.8533 20.68 45.5013 19.2112H23.4694V27.9356H36.4069C36.1429 30.1094 34.7347 33.37 31.5957 35.5731L31.5663 35.8669L38.5191 41.2719L38.9885 41.3306C43.4477 37.2181 46 31.1669 46 24.0287Z"
                                fill="#4285F4" />
                            <path
                                d="M23.4694 47C29.8061 47 35.1161 44.9144 39.0179 41.3012L31.625 35.5437C29.6301 36.9244 26.9898 37.8937 23.4987 37.8937C17.2793 37.8937 12.0281 33.7812 10.1505 28.1412L9.88649 28.1706L2.61097 33.7812L2.52296 34.0456C6.36608 41.7125 14.287 47 23.4694 47Z"
                                fill="#34A853" />
                            <path
                                d="M10.1212 28.1413C9.62245 26.6725 9.32908 25.1156 9.32908 23.5C9.32908 21.8844 9.62245 20.3275 10.0918 18.8588V18.5356L2.75765 12.8369L2.52296 12.9544C0.909439 16.1269 0 19.7106 0 23.5C0 27.2894 0.909439 30.8731 2.49362 34.0456L10.1212 28.1413Z"
                                fill="#FBBC05" />
                            <path
                                d="M23.4694 9.07688C27.8699 9.07688 30.8622 10.9863 32.5344 12.5725L39.1645 6.11C35.0867 2.32063 29.8061 0 23.4694 0C14.287 0 6.36607 5.2875 2.49362 12.9544L10.0918 18.8588C11.9987 13.1894 17.25 9.07688 23.4694 9.07688Z"
                                fill="#EB4335" />
                        </svg>
                        Sign up with Google
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
use Livewire\Attributes\{Layout, Title};
use Livewire\Volt\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;

new #[Layout('components.layouts.auth')] #[Title('Login')] class extends Component {
    public $email = '';
    public $password = '';
    public $remember;

    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['required', 'boolean'],
        ];
    }
    public function login()
    {
        $this->validate();

        if (
            !Auth::attempt(
                [
                    'email' => $this->email,
                    'password' => $this->password,
                ],
                // $this->remember,
            )
        ) {
            Session::flash('error', 'Email atau password yang Anda masukkan salah.');

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
                'password' => ' ',
            ]);
        }

        request()->session()->regenerate();
        $user = Auth::user();
        $user->status = 'Aktif';
        $user->save();
        if ($user->role === 'admin') {
            $this->redirect(route('dashboard-admin'), navigate: true);
        } else {
            $this->redirect(route('dashboard-konsumen'), navigate: true);
        }
    }
};
?>

<div class="w-full h-11/12 max-w-7xl mx-auto">
    <!-- Main Content -->
    <div class="w-auto relative overflow-hidden ">
        <!-- Form Container -->
        <div
            class="w-full bg-neutral-600/20 dark:bg-neutral-400/20 backdrop-blur-[3px] border border-neutral-600/20 dark:border-neutral-400/20 rounded-2xl shadow-sm max-w-md mx-auto">
            <div class="p-5 sm:p-6">
                <!-- Thema and back button -->
                <div class="flex justify-between items-center">
                    <a href="/"
                        class="inline-flex items-center hover:text-gray-800 ">
                        <x-icon name="c-arrow-left" label="Kembali" class="mr-1" />
                    </a>
                    <x-theme-toggle class="btn btn-circle" darkTheme="dark" lightTheme="light" class="mr-5" />
                </div>

                <!-- Logo & Title -->
                <div class="text-center">
                    <img src="{{ asset('img/logo.svg') }}" alt="Logo" width="60" class="mx-auto mb-2">
                    <h1 class="text-xl font-bold">Sign in</h1>
                    <p class="mt-1 text-sm">
                        Tidak memiliki akun ?
                        <a href="/register" class="text-blue-600 hover:underline font-medium dark:text-blue-400">
                            Register
                        </a>
                    </p>
                </div>

                <!-- Form -->
                <x-form wire:submit="login">
                    <div class="grid gap-y-3">
                        <x-input label="Email" wire:model="email" placeholder="Email..." icon="o-user"
                            hint="Masukan email" required />
                        <x-password label="Password" hint="Masukan password" wire:model="password" placeholder="Password..." clearable
                            required />

                        <!-- Checkbox & Link -->
                        <div class="flex items-center justify-between text-sm">
                            <x-checkbox label="Saya Setuju" wire:model="remember" />
                            <a href="/lupa-sandi" class="text-blue-600 hover:underline dark:text-blue-400">Lupa
                                Kata
                                Sandi?</a>
                        </div>

                        <!-- Submit -->
                        <x-button label="Masuk" type="submit" class="btn-primary text-base-100 dark:text-white" />
                    </div>
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
                        Sign in with Google
                    </x-button>
                </div>
            </div>
        </div>
    </div>
</div>

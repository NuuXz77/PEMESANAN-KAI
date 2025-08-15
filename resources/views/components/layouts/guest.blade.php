<!DOCTYPE html>
<html data-thema="dark" class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <script src="https://cdn.jsdelivr.net/npm/rellax@1.12.1/rellax.min.js"></script>
    @livewireStyles
    <title>{{ $title ?? 'Beranda' }}</title>
</head>

<body class="font-sans antialiased">
    {{-- <img src="{{ asset('img/landing-page.svg') }}" alt=""> --}}
    {{-- <div class="absolute inset-0 -z-10 h-full w-full bg-cover bg-center"
        style="background-image: radial-gradient(125% 125% at 50% 10%, #000 50%,  #fff 100%), url('{{ asset('img/landing-page.svg') }}'); background-blend-mode: overlay;">
    </div> --}}
    {{-- <div
        class="absolute inset-0 -z-10 h-full w-full bg-white bg-[linear-gradient(to_right,#f0f0f0_1px,transparent_1px),linear-gradient(to_bottom,#f0f0f0_1px,transparent_1px)] bg-[size:6rem_4rem]">
        <div
            class="absolute bottom-0 left-0 right-0 top-0 bg-[radial-gradient(circle_800px_at_100%_200px,#d5c5ff,transparent)]">
        </div>
    </div> --}}
    {{-- <div
        class="fixed inset-0 -z-10 h-full w-full
        bg-white
        bg-[linear-gradient(to_right,#f0f0f0_1px,transparent_1px),linear-gradient(to_bottom,#f0f0f0_1px,transparent_1px)]
        bg-[size:6rem_4rem]
        dark:bg-base-100
        dark:bg-[linear-gradient(to_right,#222_1px,transparent_1px),linear-gradient(to_bottom,#222_1px,transparent_1px)]
        dark:bg-[size:6rem_4rem]">
        <div
            class="fixed bottom-0 left-0 right-0 top-0
            bg-[radial-gradient(circle_500px_at_50%_200px,#2C296E,transparent)]
            dark:bg-[radial-gradient(circle_500px_at_50%_200px,#2C296E,transparent)]">
        </div>
    </div> --}}
    <div class="relative h-full w-full dark:bg-neutral-900 bg-neutral-300">
        <div class="fixed inset-0 bg-secondary bg-[size:20px_20px] opacity-20 blur-[100px]"></div>
    </div>

    <div
        class="navbar fixed z-10 top-0 left-0 right-0 mx-auto my-4 max-w-[95%] rounded-2xl bg-neutral-600/20 dark:bg-neutral-400/20 backdrop-blur-[3px] border border-neutral-600/20 dark:border-neutral-400/20">
        <div class="navbar-start">
            <div class="dropdown">
                <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </div>
                <ul tabindex="0"
                    class=" menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                    <li><a href="#beranda" class="btn">Beranda</a></li>
                    <li><a href="#layanan" class="btn">Layanan</a></li>
                    <li><a href="#proses" class="btn">Proses</a></li>
                    <li><a href="#kontak" class="btn">Kontak</a></li>
                </ul>
            </div>
            <a class="btn btn-ghost text-xl"><img src="{{ asset('img/logo.svg') }}" alt="" width="80"></a>
        </div>
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <li><a href="#beranda">Beranda</a></li>
                <li><a href="#layanan">Layanan</a></li>
                <li><a href="#proses">Proses</a></li>
                <li><a href="#kontak">Kontak</a></li>
            </ul>
        </div>
        <div class="navbar-end rounded-2xl">
            <x-theme-toggle class="btn btn-circle" darkTheme="dark" lightTheme="light" class="mr-5" />
            <a href="/login" class="btn btn-primary btn-outline mr-5">Login</a>
            <a href="#" class="btn btn-warning">Register</a>
        </div>
    </div>


    {{-- MAIN --}}
    <x-main with-nav full-width>
        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{-- Toast --}}
    <x-toast />
    @livewireScripts
    <script>
        // Accepts any class name
        var rellax = new Rellax('.rellax');
    </script>
</body>

</html>

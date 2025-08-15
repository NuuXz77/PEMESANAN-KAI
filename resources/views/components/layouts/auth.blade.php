<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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
    <div class="relative h-full w-full bg-neutral-900">
        <div class="fixed inset-0 bg-secondary bg-[size:20px_20px] opacity-20 blur-[100px]"></div>
    </div>


    {{-- MAIN --}}
    <x-main>
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

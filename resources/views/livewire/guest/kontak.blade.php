<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <section id="kontak" class="text-base-content py-16">
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 px-4">
            {{-- Logo & Download --}}
            <div>
                <img src="{{ asset('img/logo.svg') }}" alt="KAI" class="mb-4 w-28">
                <p class="text-sm opacity-80 mb-4 text-base-content">
                    With lots of unique blocks, you can easily build a page without coding. Build your next landing
                    page.
                </p>
                <div class="flex gap-2">
                    <img src="{{ asset('img/appstore.png') }}" alt="App Store" class="h-10">
                    <img src="{{ asset('img/playstore.png') }}" alt="Google Play" class="h-10">
                </div>
            </div>

            {{-- Informasi --}}
            <div>
                <h4 class="font-bold mb-4 text-primary-content">Informasi</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="link link-hover text-base-content">Tentang Kami</a></li>
                    <li><a href="#" class="link link-hover text-base-content">Karir</a></li>
                    <li><a href="#" class="link link-hover text-base-content">Publikasi</a></li>
                </ul>
            </div>

            {{-- Kontak Kami --}}
            <div>
                <h4 class="font-bold mb-4 text-primary-content">Kontak Kami</h4>
                <ul class="space-y-2 text-sm text-base-content">
                    <li>Kantor Pusat: Jalan Perintis Kemerdekaan No. 1 Bandung 40117</li>
                    <li>Office Phone: 022-4230031, 4230039, 4230054</li>
                    <li>Email: dokumen@kai.id</li>
                    <li>Contact Center: 121 / (021) 121</li>
                    <li>Layanan Pelanggan: cs@kai.id</li>
                </ul>
            </div>
        </div>

        <div class="mt-10 text-center text-xs opacity-60 text-base-content">
            © 2025 PT KERETA API INDONESIA (PERSERO), All Rights Reserved
        </div>
    </section>
</div>
</div>

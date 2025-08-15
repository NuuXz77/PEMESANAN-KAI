<?php

use Livewire\Volt\Component;

new class extends Component {
    //
}; ?>

<div>
    <section id="layanan" class="py-16 text-center">
        {{-- <div
            class="absolute inset-0 -z-10 h-full w-full items-center px-5 py-24 [background:radial-gradient(125%_125%_at_50%_10%,#000_40%,#63e_100%)]">
        </div> --}}

        <h2 class="text-2xl md:text-3xl font-bold">MEMBERIKAN LAYANAN TERBAIK</h2>
        <p class="mt-2 opacity-80 max-w-xl mx-auto">
            Memberikan kualitas layanan yang tinggi guna mendukung penyelenggaraan perkeretaapian
        </p>


        <div class="mt-10 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6 max-w-6xl mx-auto">
            {{-- Card 1 --}}
            <x-card title="Jadwal Kereta Real-Time"
                subtitle="Pantau jadwal keberangkatan dan kedatangan kereta secara langsung dan akurat."
                class="card bg-neutral-600/20 dark:bg-neutral-400/20 rounded-2xl backdrop-blur-[1px] border border-neutral-600/20 dark:border-neutral-400/20 shadow-lg">
                <x-slot:figure>
                    {{-- <img src="https://i.pinimg.com/736x/bd/7d/10/bd7d10460d8e7d801763571680ccd402.jpg" alt="" width="200" height="200" class="rounded-2xl"> --}}
                    <x-icon name="o-credit-card" class="w-15 p-3 mt-5 bg-primary rounded-4xl" />
                </x-slot:figure>
            </x-card>
            <x-card title="Pemilihan Kursi Langsung"
                subtitle="Pilih tempat duduk favorit Anda langsung saat memesan tiket, tanpa biaya tambahan."
                class="card bg-neutral-600/20 dark:bg-neutral-400/20 rounded-2xl backdrop-blur-[1px] border border-neutral-600/20 dark:border-neutral-400/20 shadow-lg">
                <x-slot:figure>
                    {{-- <img src="https://i.pinimg.com/736x/bd/7d/10/bd7d10460d8e7d801763571680ccd402.jpg" alt="" width="200" height="200" class="rounded-2xl"> --}}
                    <x-icon name="o-credit-card" class="w-15 p-3 mt-5 bg-primary rounded-4xl" />
                </x-slot:figure>
            </x-card>
            <x-card title="Pembayaran Mudah & Aman"
                subtitle="Dukung berbagai metode pembayaran terpercaya dengan sistem keamanan berlapis."
                class="card bg-neutral-600/20 dark:bg-neutral-400/20 rounded-2xl backdrop-blur-[1px] border border-neutral-600/20 dark:border-neutral-400/20 shadow-lg">
                <x-slot:figure>
                    {{-- <img src="https://i.pinimg.com/736x/bd/7d/10/bd7d10460d8e7d801763571680ccd402.jpg" alt="" width="200" height="200" class="rounded-2xl"> --}}
                    <x-icon name="o-credit-card" class="w-15 p-3 mt-5 bg-primary rounded-4xl" />
                </x-slot:figure>
            </x-card>
            <x-card title="E-Tiket Praktis"
                subtitle="Tiket langsung dikirim ke email dan bisa ditunjukkan dari HP tanpa perlu cetak."
                class="card bg-neutral-600/20 dark:bg-neutral-400/20 rounded-2xl backdrop-blur-[1px] border border-neutral-600/20 dark:border-neutral-400/20 shadow-lg">
                <x-slot:figure>
                    {{-- <img src="https://i.pinimg.com/736x/bd/7d/10/bd7d10460d8e7d801763571680ccd402.jpg" alt="" width="200" height="200" class="rounded-2xl"> --}}
                    <x-icon name="o-credit-card" class="w-15 p-3 mt-5 bg-primary rounded-4xl" />
                </x-slot:figure>
            </x-card>
        </div>

    </section>
</div>

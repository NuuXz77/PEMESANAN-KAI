<?php

use Livewire\Volt\Component;

new class extends Component {
    // Step model
    public int $step = 2;
}; ?>

<div>
    <section id="proses"
        class="py-16  text-center">
        <h2 class="text-2xl md:text-3xl font-bold">BAGAIMANA PROSES PEMESANAN?</h2>
        <p class="mt-2 opacity-80">Di perangkat manapun proses pemesanan sama.</p>
        <ul class="glows steps steps-vertical lg:steps-horizontal gap-5 m-5">
            <li class="step step-primary">
                <x-card title="Jadwal Kereta Real-Time"
                    subtitle="Pantau jadwal keberangkatan dan kedatangan kereta secara langsung dan akurat."
                    class="card glow bg-neutral-600/20 dark:bg-neutral-400/20 rounded-2xl backdrop-blur-[1px] border border-neutral-600/20 dark:border-neutral-400/20 shadow-lg">
                    <x-slot:figure>
                        {{-- <img src="https://i.pinimg.com/736x/bd/7d/10/bd7d10460d8e7d801763571680ccd402.jpg" alt="" width="200" height="200" class="rounded-2xl"> --}}
                        <x-icon name="o-credit-card" class="w-15 p-3 mt-5 bg-primary rounded-4xl" />
                    </x-slot:figure>
                </x-card>
            </li>
            <li class="step step-primary">
                <x-card title="Jadwal Kereta Real-Time"
                    subtitle="Pantau jadwal keberangkatan dan kedatangan kereta secara langsung dan akurat."
                    class="card glow bg-neutral-600/20 dark:bg-neutral-400/20 rounded-2xl backdrop-blur-[1px] border border-neutral-600/20 dark:border-neutral-400/20 shadow-lg">
                    <x-slot:figure>
                        {{-- <img src="https://i.pinimg.com/736x/bd/7d/10/bd7d10460d8e7d801763571680ccd402.jpg" alt="" width="200" height="200" class="rounded-2xl"> --}}
                        <x-icon name="o-credit-card" class="w-15 p-3 mt-5 bg-primary rounded-4xl" />
                    </x-slot:figure>
                </x-card>
            </li>
            <li class="step step-primary">
                <x-card title="Jadwal Kereta Real-Time"
                    subtitle="Pantau jadwal keberangkatan dan kedatangan kereta secara langsung dan akurat."
                    class="card glow bg-neutral-600/20 dark:bg-neutral-400/20 rounded-2xl backdrop-blur-[1px] border border-neutral-600/20 dark:border-neutral-400/20 shadow-lg">
                    <x-slot:figure>
                        {{-- <img src="https://i.pinimg.com/736x/bd/7d/10/bd7d10460d8e7d801763571680ccd402.jpg" alt="" width="200" height="200" class="rounded-2xl"> --}}
                        <x-icon name="o-credit-card" class="w-15 p-3 mt-5 bg-primary rounded-4xl" />
                    </x-slot:figure>
                </x-card>
            </li>
            <li class="step step-primary">
                <x-card title="Jadwal Kereta Real-Time"
                    subtitle="Pantau jadwal keberangkatan dan kedatangan kereta secara langsung dan akurat."
                    class="card glow bg-neutral-600/20 dark:bg-neutral-400/20 rounded-2xl backdrop-blur-[1px] border border-neutral-600/20 dark:border-neutral-400/20 shadow-lg">
                    <x-slot:figure>
                        {{-- <img src="https://i.pinimg.com/736x/bd/7d/10/bd7d10460d8e7d801763571680ccd402.jpg" alt="" width="200" height="200" class="rounded-2xl"> --}}
                        <x-icon name="o-credit-card" class="w-15 p-3 mt-5 bg-primary rounded-4xl" />
                    </x-slot:figure>
                </x-card>
            </li>
        </ul>
    </section>

    {{-- <style>
        .glow::before {
            content: "";
            height: 100%;
            width: 100%;
            position: absolute;
            top: 0;
            left: 0;
            border-radius: inherit;
            opacity: 0;
            transition: all 0.2s ease-in-out;
        }

        .glow-content {
            background-color: #1e2125;
            border-radius: inherit;
            transition: all 0.25s;
            height: calc(100% - 2px);
            width: calc(100% - 2px);
        }

        .glow:hover {
            transform: scale(0.98);
        }

        .glow:hover::before {
            opacity: 1;
        }
    </style>

    <script>
        const cards = document.querySelectorAll(".glow");
        const wrapper = document.querySelector(".glows");

        wrapper.addEventListener("mousemove", function(event) {
            cards.forEach((glow) => {
                const rect = glow.getBoundingClientRect();
                const x = event.clientX - rect.left;
                const y = event.clientY - rect.top;

                glow.style.background =
                    `radial-gradient(960px circle at ${x}px ${y}px, rgba(59, 248, 251))`;
            });
        });
    </script> --}}
</div>

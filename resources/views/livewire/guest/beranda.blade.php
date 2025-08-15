<?php

use Livewire\Volt\Component;

new class extends Component {
    public $grouped = [
        'Admins' => [['id' => 1, 'name' => 'Mary'], ['id' => 2, 'name' => 'Giovanna'], ['id' => 3, 'name' => 'Marina']],
        'Users' => [['id' => 4, 'name' => 'John'], ['id' => 5, 'name' => 'Doe'], ['id' => 6, 'name' => 'Jane']],
    ];
}; ?>

<div id="beranda" class="mt-24 relative flex flex-col items-center justify-center text-base-content h-100">
    <div class="relative text-center px-4">
        <h1 class="text-4xl md:text-6xl font-extrabold leading-tight">
            BOOKING KAI DENGAN <br class="hidden md:block"> WEBSITE!
        </h1>
        <p class="mt-4 text-sm md:text-base opacity-80">
            Dengan adanya website ini semoga lebih mempermudah dalam pemesanan tiket di KAI (Kereta Api Indonesia).
        </p>
        <div
            class="justify-center mt-8 bg-neutral-600/20 dark:bg-neutral-400/20 rounded-3xl backdrop-blur-[1px] border border-neutral-600 dark:border-neutral-400/20 p-4 flex flex-wrap md:flex-nowrap gap-5 [background:linear-gradient(45deg,#2C296E,#2C296E,#2C296E)_padding-box,conic-gradient(from_var(--border-angle),theme(colors.slate.600/.48)_80%,_theme(colors.indigo.500)_86%,_theme(colors.indigo.300)_90%,_theme(colors.indigo.500)_94%,_theme(colors.slate.600/.48))_border-box] animate-border shadow-2xl min-w-full">

            <x-select-group :options="$grouped" wire:model="selectedUser" />
            <x-select-group :options="$grouped" wire:model="selectedUser" />
            <x-select-group :options="$grouped" wire:model="selectedUser" />
            <x-select-group :options="$grouped" wire:model="selectedUser" />
            <x-button icon="o-magnifying-glass" class="btn-warning"/>
        </div>
    </div>
    <style>
        /* From Uiverse.io by dylanharriscameron */
        .cards {
            position: relative;
            width: auto;
            height: auto;
            border-radius: 14px;
            z-index: 1111;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 20px 20px 60px #bebebe, -20px -20px 60px #ffffff;
            ;
        }

        .bg {
            position: absolute;
            top: 5px;
            left: 5px;
            width: 190px;
            height: 240px;
            z-index: 2;
            background: rgba(255, 255, 255, .95);
            backdrop-filter: blur(24px);
            border-radius: 10px;
            overflow: hidden;
            outline: 2px solid white;
        }

        .blob {
            position: absolute;
            z-index: 1;
            top: 50%;
            left: 50%;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: #ff0000;
            opacity: 1;
            filter: blur(12px);
            animation: blob-bounce 5s infinite ease;
        }

        @keyframes blob-bounce {
            0% {
                transform: translate(-100%, -100%) translate3d(0, 0, 0);
            }

            25% {
                transform: translate(-100%, -100%) translate3d(100%, 0, 0);
            }

            50% {
                transform: translate(-100%, -100%) translate3d(100%, 100%, 0);
            }

            75% {
                transform: translate(-100%, -100%) translate3d(0, 100%, 0);
            }

            100% {
                transform: translate(-100%, -100%) translate3d(0, 0, 0);
            }
        }
    </style>
</div>

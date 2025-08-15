<x-layouts.app>
    <x-form wire:submit="save2">
        <x-input label="Address" wire:model="address" />

        {{-- Notice `omit-error` --}}
        <x-input label="Number" wire:model="number" omit-error
            hint="This is required, but we suppress the error message" />

        <x-slot:actions>
            <x-button label="Click me!" class="btn-primary" type="submit" spinner="save2" />
        </x-slot:actions>
    </x-form>
</x-layouts.app>

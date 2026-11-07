<x-filament-panels::page>
    <div class="space-y-6 max-w-3xl">
        <div class="rounded-xl bg-amber-50 dark:bg-amber-950/30 p-4 text-sm text-amber-900 dark:text-amber-100">
            Demo-safe simulator. No real Meta / WhatsApp API calls are made.
        </div>

        <form wire:submit="simulate" class="space-y-4">
            {{ $this->form }}

            <x-filament::button type="submit">
                Simulate incoming message
            </x-filament::button>
        </form>

        @if($resultSummary)
            <div class="rounded-xl bg-emerald-50 dark:bg-emerald-950/30 p-4 text-sm">
                {{ $resultSummary }}
            </div>
        @endif
    </div>
</x-filament-panels::page>

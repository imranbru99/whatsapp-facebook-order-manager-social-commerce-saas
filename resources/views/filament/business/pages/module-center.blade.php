<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-xl bg-white dark:bg-gray-900 p-4 shadow-sm ring-1 ring-gray-950/5">
            <h2 class="text-lg font-semibold mb-2">Business type presets</h2>
            <div class="flex flex-wrap gap-2">
                @foreach(['fashion' => 'Fashion', 'gadgets' => 'Gadgets', 'food' => 'Food / Kitchen'] as $key => $label)
                    <x-filament::button wire:click="applyPreset('{{ $key }}')" color="gray" size="sm">
                        {{ $label }}
                    </x-filament::button>
                @endforeach
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            @foreach($modules as $key => $module)
                <div class="rounded-xl bg-white dark:bg-gray-900 p-4 shadow-sm ring-1 ring-gray-950/5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div class="font-semibold">{{ $module['name'] }}</div>
                            <div class="text-xs text-gray-500 mt-1">Group {{ $module['group'] }} · {{ $key }}</div>
                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-2">{{ $module['description'] ?? '' }}</p>
                            @if(!empty($module['dependencies']))
                                <p class="text-xs text-amber-600 mt-2">Depends on: {{ implode(', ', $module['dependencies']) }}</p>
                            @endif
                        </div>
                        <x-filament::button
                            wire:click="toggle('{{ $key }}')"
                            size="sm"
                            :color="!empty($enabled[$key]) ? 'success' : 'gray'"
                            :disabled="!empty($module['core'])"
                        >
                            {{ !empty($enabled[$key]) ? 'ON' : 'OFF' }}
                        </x-filament::button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-filament-panels::page>

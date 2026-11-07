<?php

namespace App\Filament\Business\Pages;

use App\Core\Services\ModuleRegistry;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class ModuleCenter extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static string $view = 'filament.business.pages.module-center';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?int $navigationSort = 1;

    public Collection $modules;

    public array $enabled = [];

    public function mount(ModuleRegistry $registry): void
    {
        $businessId = (int) auth()->user()->business_id;
        $this->modules = $registry->all();
        $this->enabled = array_fill_keys($registry->enabledKeys($businessId), true);
    }

    public function toggle(string $key): void
    {
        $registry = app(ModuleRegistry::class);
        $businessId = (int) auth()->user()->business_id;

        try {
            if ($registry->isCore($key)) {
                Notification::make()->title('Core modules stay enabled')->warning()->send();

                return;
            }

            if ($registry->isEnabled($businessId, $key)) {
                $registry->disable($businessId, $key);
                unset($this->enabled[$key]);
                Notification::make()->title("Disabled {$key}")->success()->send();
            } else {
                $registry->enable($businessId, $key);
                $this->enabled[$key] = true;
                Notification::make()->title("Enabled {$key}")->success()->send();
            }
        } catch (\Throwable $e) {
            Notification::make()->title($e->getMessage())->danger()->send();
        }

        $this->enabled = array_fill_keys($registry->enabledKeys($businessId), true);
    }

    public function applyPreset(string $preset): void
    {
        $registry = app(ModuleRegistry::class);
        $businessId = (int) auth()->user()->business_id;
        $registry->applyPreset($businessId, $preset);
        $this->enabled = array_fill_keys($registry->enabledKeys($businessId), true);
        Notification::make()->title("Applied {$preset} preset")->success()->send();
    }
}

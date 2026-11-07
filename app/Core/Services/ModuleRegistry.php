<?php

namespace App\Core\Services;

use App\Models\BusinessModule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class ModuleRegistry
{
    public function all(): Collection
    {
        return Cache::remember('modules.manifests', 60, function () {
            $path = config('modules.path', app_path('Modules'));
            $items = collect();

            if (! File::isDirectory($path)) {
                return $items;
            }

            foreach (File::directories($path) as $dir) {
                $manifest = $dir.'/module.json';
                if (! File::exists($manifest)) {
                    continue;
                }
                $data = json_decode(File::get($manifest), true);
                if (is_array($data) && isset($data['key'])) {
                    $items->put($data['key'], $data);
                }
            }

            return $items->sortBy('name');
        });
    }

    public function get(string $key): ?array
    {
        return $this->all()->get($key);
    }

    public function isCore(string $key): bool
    {
        $module = $this->get($key);

        return (bool) ($module['core'] ?? false) || in_array($key, config('modules.core', []), true);
    }

    public function isEnabled(int $businessId, string $key, ?int $branchId = null): bool
    {
        if ($this->isCore($key)) {
            return true;
        }

        $query = BusinessModule::query()
            ->withoutGlobalScopes()
            ->where('business_id', $businessId)
            ->where('module_key', $key)
            ->where('enabled', true);

        if ($branchId) {
            $query->where(function ($q) use ($branchId) {
                $q->whereNull('branch_id')->orWhere('branch_id', $branchId);
            });
        } else {
            $query->whereNull('branch_id');
        }

        return $query->exists();
    }

    public function enable(int $businessId, string $key, ?int $branchId = null, array $settings = []): BusinessModule
    {
        $module = $this->get($key);
        if (! $module) {
            throw new \InvalidArgumentException("Unknown module: {$key}");
        }

        foreach ($module['dependencies'] ?? [] as $dep) {
            if (! $this->isEnabled($businessId, $dep, $branchId) && ! $this->isCore($dep)) {
                $this->enable($businessId, $dep, $branchId);
            }
        }

        return BusinessModule::query()->withoutGlobalScopes()->updateOrCreate(
            [
                'business_id' => $businessId,
                'branch_id' => $branchId,
                'module_key' => $key,
            ],
            [
                'enabled' => true,
                'settings' => $settings,
            ]
        );
    }

    public function disable(int $businessId, string $key, ?int $branchId = null): void
    {
        if ($this->isCore($key)) {
            throw new \RuntimeException('Core modules cannot be disabled.');
        }

        $dependents = $this->all()->filter(function (array $m) use ($key) {
            return in_array($key, $m['dependencies'] ?? [], true);
        })->keys();

        foreach ($dependents as $depKey) {
            if ($this->isEnabled($businessId, $depKey, $branchId)) {
                throw new \RuntimeException("Cannot disable {$key}: required by {$depKey}.");
            }
        }

        BusinessModule::query()->withoutGlobalScopes()
            ->where('business_id', $businessId)
            ->where('module_key', $key)
            ->when($branchId, fn ($q) => $q->where('branch_id', $branchId), fn ($q) => $q->whereNull('branch_id'))
            ->update(['enabled' => false]);
    }

    public function applyPreset(int $businessId, string $preset): void
    {
        $keys = config("modules.presets.{$preset}", []);
        foreach ($keys as $key) {
            $this->enable($businessId, $key);
        }
    }

    public function enabledKeys(int $businessId): array
    {
        $core = config('modules.core', []);
        $enabled = BusinessModule::query()->withoutGlobalScopes()
            ->where('business_id', $businessId)
            ->where('enabled', true)
            ->pluck('module_key')
            ->all();

        return array_values(array_unique(array_merge($core, $enabled)));
    }
}

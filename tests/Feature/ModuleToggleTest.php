<?php

namespace Tests\Feature;

use App\Core\Services\ModuleRegistry;
use App\Models\Business;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ModuleToggleTest extends TestCase
{
    use RefreshDatabase;

    public function test_module_enable_disable_and_dependency_guard(): void
    {
        $business = Business::query()->create([
            'ulid' => (string) Str::ulid(),
            'name' => 'Toggle Co',
            'slug' => 'toggle-co',
        ]);

        $registry = app(ModuleRegistry::class);

        $this->assertTrue($registry->isEnabled($business->id, 'core_modules'));
        $this->assertFalse($registry->isEnabled($business->id, 'orders'));

        $registry->enable($business->id, 'orders');
        $this->assertTrue($registry->isEnabled($business->id, 'orders'));
        $this->assertTrue($registry->isEnabled($business->id, 'customers'));
        $this->assertTrue($registry->isEnabled($business->id, 'catalog'));

        $this->expectException(\RuntimeException::class);
        $registry->disable($business->id, 'customers');
    }
}

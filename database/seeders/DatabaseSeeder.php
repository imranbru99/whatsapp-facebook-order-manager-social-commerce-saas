<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['super_admin', 'owner', 'manager', 'agent', 'rider', 'accountant', 'hr', 'marketing', 'support', 'warehouse'] as $role) {
            Role::findOrCreate($role);
        }

        Plan::query()->upsert([
            [
                'name' => 'Free',
                'slug' => 'free',
                'code' => 'free',
                'price_monthly' => 0,
                'price_yearly' => 0,
                'currency' => 'BDT',
                'order_limit' => 100,
                'ai_credit_limit' => 50,
                'staff_limit' => 3,
                'channel_limit' => 2,
                'features' => json_encode(['core']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'code' => 'pro',
                'price_monthly' => 299900,
                'price_yearly' => 2999000,
                'currency' => 'BDT',
                'order_limit' => 5000,
                'ai_credit_limit' => 2000,
                'staff_limit' => 25,
                'channel_limit' => 10,
                'features' => json_encode(['ai', 'couriers', 'accounting']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'code' => 'enterprise',
                'price_monthly' => 999900,
                'price_yearly' => 9999000,
                'currency' => 'BDT',
                'order_limit' => null,
                'ai_credit_limit' => null,
                'staff_limit' => null,
                'channel_limit' => null,
                'features' => json_encode(['all']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ], ['code']);

        User::query()->updateOrCreate(
            ['email' => 'admin@platform.test'],
            [
                'name' => 'Platform Admin',
                'password' => Hash::make('password'),
                'is_platform_admin' => true,
                'is_demo' => true,
                'panel_role' => 'super_admin',
                'locale' => 'en',
            ]
        )->assignRole('super_admin');

        $this->seedCms();

        if (app()->environment('local', 'testing', 'demo')) {
            $this->call(DemoSeeder::class);
        }
    }

    protected function seedCms(): void
    {
        $pages = [
            ['home', 'Home', 'Turn Facebook & WhatsApp chats into fulfilled orders.'],
            ['features', 'Features', 'Every module group for social commerce operations.'],
            ['modules', 'Modules', 'Build your own plan by toggling modules.'],
            ['pricing', 'Pricing', 'Simple plans that scale with your order volume.'],
            ['how-it-works', 'How it works', 'From chat message to delivered parcel.'],
            ['live-demo', 'Live Demo', 'Explore the product with ready demo accounts.'],
            ['integrations', 'Integrations', 'Channels, couriers, payments, AI, attendance.'],
            ['contact', 'Contact', 'Talk to the Social Commerce team.'],
            ['terms', 'Terms of Service', 'Legal terms for using the platform.'],
            ['privacy', 'Privacy Policy', 'How we handle business and customer data.'],
            ['refund', 'Refund Policy', 'SaaS subscription refund policy.'],
            ['cookies', 'Cookie Policy', 'Cookie usage on the marketing site.'],
        ];

        foreach ($pages as [$slug, $title, $body]) {
            \App\Models\CmsPage::query()->updateOrCreate(
                ['slug' => $slug, 'locale' => 'en'],
                [
                    'title' => $title,
                    'meta_title' => $title.' | Social Commerce Order Manager',
                    'meta_description' => $body,
                    'body' => $body,
                    'is_published' => true,
                ]
            );
            \App\Models\CmsPage::query()->updateOrCreate(
                ['slug' => $slug, 'locale' => 'bn'],
                [
                    'title' => $title.' (বাংলা)',
                    'meta_title' => $title.' | সোশ্যাল কমার্স',
                    'meta_description' => $body,
                    'body' => $body,
                    'is_published' => true,
                ]
            );
        }

        \App\Models\BlogPost::query()->updateOrCreate(
            ['slug' => 'chat-to-order-guide'],
            [
                'title' => 'How Chat-to-Order turns Bangla DMs into invoices',
                'locale' => 'en',
                'excerpt' => 'A walkthrough of AI order extraction for Facebook and WhatsApp sellers.',
                'body' => 'Agents paste or sync chats, verify AI fields, confirm, pack, and ship.',
                'is_published' => true,
                'published_at' => now(),
            ]
        );
    }
}

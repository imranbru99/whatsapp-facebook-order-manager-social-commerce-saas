<?php

namespace App\Console\Commands;

use Database\Seeders\DemoSeeder;
use Illuminate\Console\Command;

class DemoSeedCommand extends Command
{
    protected $signature = 'demo:seed {--fresh : Run migrate:fresh before seeding demo data}';

    protected $description = 'Seed demo businesses, users, catalog, orders, and conversations';

    public function handle(): int
    {
        if ($this->option('fresh')) {
            $this->call('migrate:fresh', ['--force' => true]);
            $this->call('db:seed', ['--force' => true]);

            return self::SUCCESS;
        }

        $this->call('db:seed', [
            '--class' => DemoSeeder::class,
            '--force' => true,
        ]);

        $this->info('Demo data ready.');
        $this->line('Platform: admin@platform.test / password');
        $this->line('Owner:    owner@demo-fashion.test / password');
        $this->line('Agent:    agent@demo-fashion.test / password');
        $this->line('Rider:    rider@demo-fashion.test / password');

        return self::SUCCESS;
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('code')->unique();
            $table->unsignedInteger('price_monthly')->default(0);
            $table->unsignedInteger('price_yearly')->default(0);
            $table->string('currency', 3)->default('BDT');
            $table->unsignedInteger('order_limit')->nullable();
            $table->unsignedInteger('ai_credit_limit')->nullable();
            $table->unsignedInteger('staff_limit')->nullable();
            $table->unsignedInteger('channel_limit')->nullable();
            $table->json('features')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('type')->default('generic');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('currency', 3)->default('BDT');
            $table->string('timezone')->default('Asia/Dhaka');
            $table->string('locale', 5)->default('en');
            $table->string('logo_path')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_demo')->default(false);
            $table->boolean('is_active')->default(true);
            $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['business_id', 'code']);
            $table->index(['business_id', 'is_active']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('business_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->after('business_id')->constrained()->nullOnDelete();
            $table->string('phone')->nullable()->after('email');
            $table->string('locale', 5)->default('en')->after('phone');
            $table->boolean('is_platform_admin')->default(false)->after('locale');
            $table->boolean('is_demo')->default(false)->after('is_platform_admin');
            $table->string('panel_role')->nullable()->after('is_demo');
            $table->json('meta')->nullable()->after('panel_role');
        });

        Schema::create('business_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('module_key');
            $table->boolean('enabled')->default(true);
            $table->json('settings')->nullable();
            $table->timestamps();
            $table->unique(['business_id', 'branch_id', 'module_key'], 'business_modules_unique');
            $table->index(['business_id', 'module_key', 'enabled']);
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('active');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('renews_at')->nullable();
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('settable');
            $table->string('group')->default('general');
            $table->string('key');
            $table->json('value')->nullable();
            $table->timestamps();
            $table->unique(['settable_type', 'settable_id', 'group', 'key'], 'settings_unique');
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');
            $table->nullableMorphs('subject');
            $table->json('properties')->nullable();
            $table->string('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->index(['business_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('business_modules');
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('business_id');
            $table->dropConstrainedForeignId('branch_id');
            $table->dropColumn(['phone', 'locale', 'is_platform_admin', 'is_demo', 'panel_role', 'meta']);
        });
        Schema::dropIfExists('branches');
        Schema::dropIfExists('businesses');
        Schema::dropIfExists('plans');
    }
};

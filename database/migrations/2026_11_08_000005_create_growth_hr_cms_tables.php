<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('stage')->default('new');
            $table->unsignedInteger('value')->default(0);
            $table->string('source')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('channel')->default('whatsapp');
            $table->string('status')->default('draft');
            $table->text('content')->nullable();
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->unsignedInteger('recipients_count')->default(0);
            $table->timestamps();
        });

        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('type')->default('percent'); // percent, fixed
            $table->unsignedInteger('value')->default(0);
            $table->unsignedInteger('min_order')->default(0);
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['business_id', 'code']);
        });

        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('number');
            $table->string('subject');
            $table->string('status')->default('open');
            $table->string('priority')->default('normal');
            $table->text('body')->nullable();
            $table->timestamps();
            $table->unique(['business_id', 'number']);
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('body')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_code')->nullable();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('designation')->nullable();
            $table->string('department')->nullable();
            $table->date('joined_at')->nullable();
            $table->unsignedInteger('salary')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('hr_attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->timestamp('check_in')->nullable();
            $table->timestamp('check_out')->nullable();
            $table->string('source')->default('manual'); // manual, attendance_hub
            $table->string('status')->default('present');
            $table->timestamps();
            $table->unique(['employee_id', 'date']);
        });

        Schema::create('document_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->string('name');
            $table->string('type')->default('invoice');
            $table->string('locale', 5)->default('en');
            $table->longText('body_html');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('title');
            $table->string('locale', 5)->default('en');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->longText('body')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->unique(['slug', 'locale']);
        });

        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->string('title');
            $table->string('locale', 5)->default('en');
            $table->text('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->unique(['slug', 'locale']);
        });

        Schema::create('notifications_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('channel')->default('in_app');
            $table->string('event');
            $table->string('title');
            $table->text('body')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('ai_extractions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('conversation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->text('source_text');
            $table->json('extracted')->nullable();
            $table->decimal('confidence', 5, 2)->default(0);
            $table->boolean('used_mock')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_extractions');
        Schema::dropIfExists('notifications_log');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('cms_pages');
        Schema::dropIfExists('document_templates');
        Schema::dropIfExists('hr_attendance_logs');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('coupons');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('leads');
    }
};

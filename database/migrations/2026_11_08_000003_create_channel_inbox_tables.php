<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type'); // facebook, instagram, whatsapp, telegram, manual
            $table->string('name');
            $table->string('external_id')->nullable();
            $table->text('access_token')->nullable();
            $table->string('status')->default('connected');
            $table->boolean('is_mock')->default(true);
            $table->json('settings')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
            $table->index(['business_id', 'type']);
        });

        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('channel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('external_thread_id')->nullable();
            $table->string('status')->default('open');
            $table->string('subject')->nullable();
            $table->unsignedInteger('unread_count')->default(0);
            $table->timestamp('last_message_at')->nullable();
            $table->json('labels')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->index(['business_id', 'status', 'last_message_at']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('conversation_id')->references('id')->on('conversations')->nullOnDelete();
        });

        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('direction'); // inbound, outbound, system
            $table->string('sender_type')->default('customer');
            $table->string('external_id')->nullable();
            $table->text('body')->nullable();
            $table->string('content_type')->default('text');
            $table->json('attachments')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            $table->index(['conversation_id', 'sent_at']);
            $table->unique(['business_id', 'external_id']);
        });

        Schema::create('canned_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('body');
            $table->string('shortcut')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('canned_replies');
        Schema::dropIfExists('messages');
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['conversation_id']);
        });
        Schema::dropIfExists('conversations');
        Schema::dropIfExists('channels');
    }
};

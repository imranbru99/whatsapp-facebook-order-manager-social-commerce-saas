<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courier_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('provider'); // steadfast, pathao, redx, custom, own
            $table->string('name');
            $table->boolean('is_mock')->default(true);
            $table->boolean('is_active')->default(true);
            $table->json('credentials')->nullable();
            $table->json('settings')->nullable();
            $table->timestamps();
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('courier_account_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('rider_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tracking_number')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedInteger('cod_amount')->default(0);
            $table->unsignedInteger('courier_charge')->default(0);
            $table->json('meta')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->index(['business_id', 'tracking_number']);
        });

        Schema::create('shipment_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->string('description')->nullable();
            $table->timestamp('happened_at')->nullable();
            $table->timestamps();
        });

        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('number');
            $table->string('status')->default('requested');
            $table->string('reason')->nullable();
            $table->unsignedInteger('refund_amount')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['business_id', 'number']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('method'); // cod, manual, bkash, stripe, sslcommerz, paypal
            $table->string('gateway')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedInteger('amount')->default(0);
            $table->string('currency', 3)->default('BDT');
            $table->string('trx_id')->nullable();
            $table->string('proof_path')->nullable();
            $table->json('meta')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->index(['business_id', 'status']);
        });

        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->string('type'); // asset, liability, equity, income, expense
            $table->timestamps();
            $table->unique(['business_id', 'code']);
        });

        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('number');
            $table->date('entry_date');
            $table->string('memo')->nullable();
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->timestamps();
            $table->unique(['business_id', 'number']);
        });

        Schema::create('journal_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained()->cascadeOnDelete();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('debit')->default(0);
            $table->unsignedInteger('credit')->default(0);
            $table->string('memo')->nullable();
            $table->timestamps();
        });

        Schema::create('cash_drawers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->unsignedInteger('opening_balance')->default(0);
            $table->unsignedInteger('current_balance')->default(0);
            $table->boolean('is_open')->default(false);
            $table->timestamps();
        });

        Schema::create('cash_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_drawer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type'); // in, out, handover
            $table->unsignedInteger('amount');
            $table->string('reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_movements');
        Schema::dropIfExists('cash_drawers');
        Schema::dropIfExists('journal_lines');
        Schema::dropIfExists('journal_entries');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('returns');
        Schema::dropIfExists('shipment_events');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('courier_accounts');
    }
};

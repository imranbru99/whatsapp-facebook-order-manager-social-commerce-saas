<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('preferred_channel')->nullable();
            $table->string('language', 5)->default('bn');
            $table->boolean('is_vip')->default(false);
            $table->boolean('is_blacklisted')->default(false);
            $table->unsignedInteger('lifetime_value')->default(0);
            $table->unsignedTinyInteger('risk_score')->default(0);
            $table->json('tags')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['business_id', 'phone']);
        });

        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('label')->nullable();
            $table->string('line1');
            $table->string('line2')->nullable();
            $table->string('district')->nullable();
            $table->string('thana')->nullable();
            $table->string('area')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country', 2)->default('BD');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
            $table->unique(['business_id', 'slug']);
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->timestamps();
            $table->unique(['business_id', 'slug']);
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('sku')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->unsignedInteger('cost_price')->default(0);
            $table->boolean('track_inventory')->default(true);
            $table->boolean('is_active')->default(true);
            $table->json('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['business_id', 'slug']);
            $table->index(['business_id', 'sku']);
        });

        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->json('attributes')->nullable();
            $table->unsignedInteger('price')->nullable();
            $table->unsignedInteger('cost_price')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['business_id', 'sku']);
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });

        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('quantity')->default(0);
            $table->integer('reserved')->default(0);
            $table->integer('reorder_point')->default(0);
            $table->timestamps();
            $table->unique(['warehouse_id', 'product_id', 'product_variant_id'], 'stock_items_unique');
        });

        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('stock_item_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->integer('quantity');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
            $table->index(['business_id', 'created_at']);
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('number');
            $table->string('status')->default('draft');
            $table->unsignedInteger('total')->default(0);
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
            $table->unique(['business_id', 'number']);
        });

        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('unit_cost')->default(0);
            $table->unsignedInteger('received_quantity')->default(0);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->ulid('ulid')->unique();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('conversation_id')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('number');
            $table->string('status')->default('draft');
            $table->string('source')->default('manual');
            $table->string('payment_status')->default('unpaid');
            $table->string('payment_method')->nullable();
            $table->unsignedInteger('subtotal')->default(0);
            $table->unsignedInteger('discount_total')->default(0);
            $table->unsignedInteger('tax_total')->default(0);
            $table->unsignedInteger('delivery_charge')->default(0);
            $table->unsignedInteger('grand_total')->default(0);
            $table->unsignedInteger('paid_total')->default(0);
            $table->unsignedInteger('due_total')->default(0);
            $table->string('currency', 3)->default('BDT');
            $table->string('shipping_name')->nullable();
            $table->string('shipping_phone')->nullable();
            $table->text('shipping_address')->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->string('public_token')->unique();
            $table->json('meta')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['business_id', 'number']);
            $table->index(['business_id', 'status']);
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('sku')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedInteger('unit_price')->default(0);
            $table->unsignedInteger('discount')->default(0);
            $table->unsignedInteger('tax')->default(0);
            $table->unsignedInteger('line_total')->default(0);
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('purchase_order_items');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('stock_items');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('customer_addresses');
        Schema::dropIfExists('customers');
    }
};

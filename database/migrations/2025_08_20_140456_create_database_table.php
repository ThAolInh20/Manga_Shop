<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        // Accounts
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->integer('role');
            $table->string('address', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->date('birth')->nullable();
            $table->string('gender', 50)->nullable();
            $table->dateTime('last_login')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->text('detail')->nullable();
            $table->timestamps();
        });

        // Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name', 255);
            $table->string('code', 50)->nullable();
            $table->integer('quantity')->default(0);
            $table->string('images', 255)->nullable();
            $table->string('author', 50)->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('sale', 10, 2)->default(0);
            $table->text('detail')->nullable();
            $table->string('status', 50)->default('active');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->dateTime('order_date')->nullable();
            $table->dateTime('deliver_date')->nullable();
            $table->integer('order_status')->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->string('shipping_address', 255)->nullable();
            $table->decimal('total_price', 10, 2)->default(0);
            $table->integer('payment_status')->default(0);
            $table->timestamps();
        });

        // Product_Order (pivot)
        Schema::create('product_orders', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->primary(['product_id', 'order_id']);
        });

        // Comments
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text('detail')->nullable();
            $table->integer('rate')->nullable();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->timestamps();
        });

        // Carts
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['account_id', 'product_id']);
        });

        // Wishlists
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['account_id', 'product_id']);
        });

        // Suppliers
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('address', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('tax_code', 50)->nullable();
            $table->timestamps();
        });

        // Product_Suppliers
        Schema::create('product_suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete();
            $table->date('date_import')->nullable();
            $table->decimal('import_price', 10, 2);
            $table->integer('quantity');
            $table->text('detail')->nullable();
            $table->timestamps();
        });

        // Vouchers
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->integer('sale');
            $table->boolean('is_active')->default(true);
            $table->date('date_end')->nullable();
            $table->integer('max_discount')->default(0);
            $table->timestamps();
        });

        // Website_Customs
        Schema::create('website_customs', function (Blueprint $table) {
            $table->id();
            $table->string('site_name', 50)->nullable();
            $table->integer('color')->nullable();
            $table->string('email', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->text('detail')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('website_customs');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('product_suppliers');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('product_orders');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('accounts');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::table('products', function (Blueprint $table) {
            $table->integer('age')->nullable()->after('name');
            $table->string('images_sup', 255)->nullable()->after('images');
            $table->string('publisher', 50)->nullable()->after('images_sup');
            $table->string('language', 50)->nullable()->after('is_active');
            $table->string('weight', 50)->nullable()->after('language');
            $table->string('size', 50)->nullable()->after('weight');
            $table->integer('quantity_buy')->nullable()->after('size');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('name_recipient', 50)->nullable()->after('payment_status');
            $table->string('update_by', 50)->nullable()->after('name_recipient');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->string('created_by', 50)->nullable()->after('updated_at');
            $table->string('updated_by', 50)->nullable()->after('created_by');
        });

        

        Schema::table('product_suppliers', function (Blueprint $table) {
            $table->integer('import_by')->nullable()->after('date_import');
        });

        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropUnique(['code']); // nếu bạn không muốn unique
        });

        Schema::table('website_customs', function (Blueprint $table) {
            $table->dropTimestamps(); // vì bảng gốc không có created_at/updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['age','images_sup','publisher','language','weight','size','quantity_buy']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['name_recipient','update_by']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['created_by','updated_by']);
        });

     

        Schema::table('product_suppliers', function (Blueprint $table) {
            $table->dropColumn('import_by');
        });

        Schema::table('vouchers', function (Blueprint $table) {
            $table->unique('code');
        });

        Schema::table('website_customs', function (Blueprint $table) {
            $table->timestamps();
        });
    }
};

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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_fee',
                'shipping_address',
                'phone_recipient',
                'name_recipient',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
           $table->integer('shipping_fee')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('phone_recipient')->nullable();
            $table->string('name_recipient')->nullable();
        });
    }
};

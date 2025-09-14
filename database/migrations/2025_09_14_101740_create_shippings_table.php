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
        Schema::create('shippings', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('account_id');
            $table->string('name_recipient', 50)->nullable();
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->string('shipping_address', 255)->nullable();
            $table->string('phone_recipient', 255)->nullable();
            $table->timestamps();

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};

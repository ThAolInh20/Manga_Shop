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
        Schema::create('website', function (Blueprint $table) {
            $table->id(); // tự động tạo cột id
            $table->string('address', 50)->nullable();
            $table->string('hotline', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('buy_guide', 50)->nullable();
            $table->string('return_policy', 50)->nullable();
            $table->string('private_policy', 50)->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website');
    }
};

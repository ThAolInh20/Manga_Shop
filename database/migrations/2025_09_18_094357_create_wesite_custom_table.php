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
        // Drop bảng nếu tồn tại
        Schema::dropIfExists('website_customs');

        // Tạo bảng mới
        Schema::create('website_customs', function (Blueprint $table) {
            $table->id(); // id auto increment
            $table->string('address', 50)->nullable();
            $table->string('hotline', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('primary_color', 50)->nullable();
            $table->string('background_color', 50)->nullable();
            $table->string('background', 50)->nullable(); // có thể là url ảnh background
            $table->string('font_family', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_customs');
    }
};

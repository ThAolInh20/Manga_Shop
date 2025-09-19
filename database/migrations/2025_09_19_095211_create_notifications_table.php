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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained('accounts')->onDelete('cascade'); // user liên quan
            $table->string('title');           // tiêu đề thông báo
            $table->text('content');           // nội dung thông báo
            $table->boolean('is_read')->default(false);       // đã đọc hay chưa
            $table->boolean('is_important')->default(false); // thông báo quan trọng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};

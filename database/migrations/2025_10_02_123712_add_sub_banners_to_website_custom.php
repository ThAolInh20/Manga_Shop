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
        Schema::table('website_customs', function (Blueprint $table) {
            $table->json('sub_banners')->nullable()->after('banner_main');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_customs', function (Blueprint $table) {
            $table->json('sub_banners')->nullable()->after('banner_main');
        });
    }
};

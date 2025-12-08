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
        Schema::table('product_suppliers', function (Blueprint $table) {
            // $table->decimal('import_price', 12, 2)->nullable()->after('supplier_id');   // giá nhập
            // $table->integer('import_quantity')->nullable()->after('import_price');       // số lượng nhập
            // $table->text('detail')->nullable()->after('import_quantity');                // chi tiết
            // $table->timestamp('imported_at')->nullable()->after('detail');               // thời gian nhập
            // $table->unsignedBigInteger('import_by')->nullable()->after('imported_at');   // người nhập
            // Nếu muốn liên kết với bảng accounts/users
            // $table->foreign('import_by')->references('id')->on('accounts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_suppliers', function (Blueprint $table) {
            $table->dropForeign(['import_by']);
            $table->dropColumn(['import_price', 'import_quantity', 'detail', 'imported_at', 'import_by']);
        });
    }
};

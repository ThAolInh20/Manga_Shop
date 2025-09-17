<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSupplier extends Model
{
    use HasFactory;
    public $timestamps = true;


    protected $table = 'product_suppliers';

    protected $fillable = [
        'product_id', 'supplier_id', 'import_at', 
        'import_price', 'import_quantity', 'detail'
    ];
     public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Quan hệ tới Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    protected static function booted()
    {
        static::created(function ($productSupplier) {
            $product = $productSupplier->product;
            if ($product) {
                $product->quantity += $productSupplier->quantity;
                $product->save();
            }
        });
        
        static::updated(function ($productSupplier) {
            $product = $productSupplier->product;
            if ($product) {
                // Lấy số lượng cũ trước khi update
                $oldQuantity = $productSupplier->getOriginal('quantity');
                $newQuantity = $productSupplier->quantity;

                $diff = $newQuantity - $oldQuantity; // tính chênh lệch
                $product->quantity += $diff;

                if ($product->quantity < 0) {
                    $product->quantity = 0;
                }

                $product->save();
            }
        });

        // Nếu xóa nhập hàng => trừ đi số lượng
        static::deleted(function ($productSupplier) {
            $product = $productSupplier->product;
            if ($product) {
                $product->quantity -= $productSupplier->quantity;
                if ($product->quantity < 0) {
                    $product->quantity = 0;
                }
                $product->save();
            }
        });
    }
}

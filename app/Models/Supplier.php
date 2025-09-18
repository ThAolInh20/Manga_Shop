<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'suppliers';

    protected $fillable = [
        'name', 'address', 'phone', 'email', 'tax_code','contract','is_active','link_contract'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_suppliers')
             ->withPivot('import_price', 'quantity', 'detail','import_by')
            ->withTimestamps();
    }
    public function productSuppliers(){
         return $this->hasMany(ProductSupplier::class, 'supplier_id', 'id');
    }
}

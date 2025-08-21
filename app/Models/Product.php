<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id', 'name', 'code', 'quantity', 
        'images', 'author', 'price', 'sale', 
        'detail', 'status', 'is_active', 'column'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'product_order')
            ->withPivot('quantity', 'price');
    }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'product_supplier')
            ->withPivot('date_import', 'import_price', 'quantity', 'detail');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}

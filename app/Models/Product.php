<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ActiveScope;
class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
         'category_id',
        'name',
        'age',
        'author',
        'images',
        'images_sup',
        'publisher',
        'quantity', 
        'price',
        'sale', 
        'detail',
        'categ',
        'status',
        'is_active',
        'language',
        'weight',
        'size',
        'quantity_buy',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'product_orders')
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

    // Sản phẩm dc ai thích
    public function likedByUsers()
    {
        return $this->belongsToMany(Account::class, 'wishlists', 'product_id', 'account_id');
    }
    // protected static function booted()
    // {
    //     static::addGlobalScope(new ActiveScope);
    // }
}

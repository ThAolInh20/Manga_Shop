<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\ActiveScope;
class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    public $timestamps = true;

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
        'price_sale',
        'detail',
        'categ',
        'status',
        'is_active',
        'language',
        'weight',
        'size',
        'quantity_buy',
    ];
    protected static function booted()
    {
        static::saving(function ($product) {
            if (!is_null($product->price) && !is_null($product->sale)) {
                $product->price_sale = $product->price - ($product->price * $product->sale / 100);
            } else {
                // Nếu chưa có sale thì để = giá gốc
                $product->price_sale = $product->price;
            }
        });
    }
    public function buy($quantity){
        if($this->quantity >= $quantity){
            $this->quantity -= $quantity;
            $this->quantity_buy += $quantity;
            $this->save();
            return true;
        }
        return false;
    }
    public function cancel($quantity){
        $this->quantity += $quantity;
        if($this->quantity_buy >= $quantity){
            $this->quantity_buy -= $quantity;
        }else{
            $this->quantity_buy = 0;
        }
        $this->save();
        return true;
    }

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
        return $this->belongsToMany(Supplier::class, 'product_suppliers')
            ->withPivot('created_at', 'import_price', 'quantity', 'detail');
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

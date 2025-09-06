<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Account extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'accounts';

    protected $fillable = [
        'name',
        'email',
        'image',
        'password',
        'role',
        'address',
        'phone',
        'birth',
        'gender',
        'last_login',
        'is_active',
    ];
    protected $hidden = [
        'password',
        'remember_token',
        
    ];

    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function wishlist()
    {
        return $this->hasMany(Wishlist::class);
    }
    // Sản phẩm user đã thích
    public function likedProducts()
{
    return $this->belongsToMany(Product::class, 'wishlists', 'account_id', 'product_id');
}

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }
}

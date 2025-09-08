<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
     use HasFactory;

    protected $table = 'carts';
    public $timestamps = true;

    protected $fillable = ['account_id', 'product_id','quantity','price'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

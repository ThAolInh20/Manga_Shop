<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Shipping extends Model
{
      protected $fillable = [
        'account_id',
        'name_recipient',
        'shipping_fee',
        'shipping_address',
        'phone_recipient',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

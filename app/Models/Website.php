<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Website extends Model
{
     use HasFactory;

    protected $table = 'website';

    protected $fillable = [
        'address',
        'hotline',
        'email',
        'buy_guide',
        'return_policy',
        'private_policy'
    ];
}

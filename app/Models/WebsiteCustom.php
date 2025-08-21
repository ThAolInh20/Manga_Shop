<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteCustom extends Model
{
    use HasFactory;

    protected $table = 'website_customs';

    protected $fillable = ['site_name', 'color', 'email', 'phone', 'detail'];
}

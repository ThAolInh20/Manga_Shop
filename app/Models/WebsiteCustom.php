<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebsiteCustom extends Model
{
    use HasFactory;

    protected $table = 'website_customs';

    protected $fillable = [
        'address',
        'hotline',
        'email',
        'primary_color',
        'background_color',
        'background',
        'font_family',
        'logo',
        'banner_main',
        'sub_banners'
    ];
}

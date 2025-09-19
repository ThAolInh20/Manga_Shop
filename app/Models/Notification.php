<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    
    protected $table = 'notifications';
    public $timestamps = true;
    protected $fillable = [
        'account_id',
        'title',
        'content',
        'is_read',
        'is_important',
    ];

    /**
     * Notification thuộc về một account/user
     */
    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * Scope để lấy thông báo chưa đọc
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope để lấy thông báo quan trọng
     */
    public function scopeImportant($query)
    {
        return $query->where('is_important', true);
    }
}

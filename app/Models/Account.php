<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


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
        'updated_by'
    ];
    protected $hidden = [
        'password',
        'remember_token',
        
    ];

     // Đếm account yêu cầu xóa (is_active = 0)
    public static function countInactive()
    {
        return static::where('is_active', 0)->count();
    }

    // Đếm account tạo trong 1 tuần gần nhất
    public static function countNewThisWeek()
    {
        return static::where('created_at', '>=', now()->subWeek())->count();
    }
    protected static function booted()
        {
            static::saving(function ($account) {
                if (Auth::check()) {
                    $account->updated_by = Auth::user()->id;
                }
                if ($account->isDirty('is_active') && $account->is_active == 0) {
                    Mail::send('user.emails.account_deactivated', ['account' => $account], function ($m) use ($account) {
                        $m->to($account->email, $account->name)
                        ->subject('Tài khoản của bạn đã yêu cầu hủy');
                    });
                }
            });
            static::deleted(function ($account) {
        // Gửi email thông báo khi xóa
                if ($account->email) {
                    Mail::send('user.emails.account_deleted', ['account' => $account], function ($message) use ($account) {
                        $message->to($account->email)
                                ->subject('Tài khoản đã được xóa thành công');
                    });
                }
            });
        }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function updatedBy()
    {
        return $this->belongsTo(Account::class, 'updated_by');
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
    public function shipping() {
        return $this->hasMany(Shipping::class);
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = ['name', 'detail', 'created_by', 'updated_by'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function updatedBy()
    {
        return $this->belongsTo(Account::class, 'updated_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(Account::class, 'created_by');
    }
    protected static function booted()
    {
        static::deleting(function ($category) {
            // Khi xoá category, update tất cả sản phẩm về category_id = id gốc
            $category->products()->update(['category_id' => 2]);

           if ($category->id == 2) {
                    // Ngăn chặn xóa
                return false; // trả về false để hủy delete
            }
        });
        // Khi tạo -> gán created_by
        static::creating(function ($category) {
            if (Auth::check()) {
                $category->created_by = Auth::id();
            }
      });

    // Khi update -> gán updated_by
        static::updating(function ($category) {
            if (Auth::check()) {
                $category->updated_by = Auth::id();
            }
        });
    }
}

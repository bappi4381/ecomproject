<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'subcategory_id', 'name','author','publisher','publishing_date', 'description', 
        'price', 'discount','discounted_price', 'stock', 'product_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $latest = Product::latest('id')->first();
            $number = $latest ? intval(substr($latest->product_id ?? 'PROD-0000', 5)) + 1 : 1;
            $product->product_id = 'PROD-' . str_pad($number, 4, '0', STR_PAD_LEFT);
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}

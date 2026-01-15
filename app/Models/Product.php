<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'base_price',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function availableVariants()
    {
        return $this->hasMany(ProductVariant::class)->where('is_available', true)->where('stock_quantity', '>', 0);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getTotalStockAttribute()
    {
        return $this->variants()->sum('stock_quantity');
    }

    public function getMinPriceAttribute()
    {
        $minAdjustment = $this->variants()->min('price_adjustment') ?? 0;
        return $this->base_price + $minAdjustment;
    }

    public function getMaxPriceAttribute()
    {
        $maxAdjustment = $this->variants()->max('price_adjustment') ?? 0;
        return $this->base_price + $maxAdjustment;
    }
}

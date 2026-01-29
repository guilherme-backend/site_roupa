<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'size',
        'color',
        'color_hex',
        'sku',
        'price_adjustment',
        'stock_quantity',
        'is_available',
    ];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getNameAttribute()
    {
        return "{$this->size}";
    }

    public function getFinalPriceAttribute()
    {
        return $this->product->base_price + $this->price_adjustment;
    }

    public function isInStock()
    {
        return $this->is_available && $this->stock_quantity > 0;
    }

    public function decrementStock($quantity)
    {
        if ($this->stock_quantity >= $quantity) {
            $this->decrement('stock_quantity', $quantity);
            return true;
        }
        return false;
    }

    public function incrementStock($quantity)
    {
        $this->increment('stock_quantity', $quantity);
    }
}

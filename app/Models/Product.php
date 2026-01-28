<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    public $stock_quantity;
    public $sizes = [];

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'base_price',
        'is_active',
        'is_featured',
        'main_image',
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

        static::created(function ($product) {
            $product->syncSimpleStockAndSizes();
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updated(function ($product) {
            $product->syncSimpleStockAndSizes();
        });
    }

    public function syncSimpleStockAndSizes()
    {
        if ($this->stock_quantity === null) return;

        $sizes = is_array($this->sizes) ? $this->sizes : [];
        
        // Se não tiver tamanhos selecionados ou a categoria não permitir tamanhos
        if (empty($sizes) || ($this->category && !$this->category->has_sizes)) {
            $this->variants()->updateOrCreate(
                ['product_id' => $this->id, 'size' => 'U'],
                [
                    'stock_quantity' => $this->stock_quantity,
                    'color' => 'Padrão',
                    'is_available' => true,
                    'sku' => 'PROD-' . $this->id . '-U'
                ]
            );
            
            // Remove outras variantes se existirem
            $this->variants()->where('size', '!=', 'U')->delete();
        } else {
            // Divide o estoque entre os tamanhos selecionados
            $stockPerSize = floor($this->stock_quantity / count($sizes));
            $extraStock = $this->stock_quantity % count($sizes);

            foreach ($sizes as $index => $size) {
                $this->variants()->updateOrCreate(
                    ['product_id' => $this->id, 'size' => $size],
                    [
                        'stock_quantity' => $stockPerSize + ($index === 0 ? $extraStock : 0),
                        'color' => 'Padrão',
                        'is_available' => true,
                        'sku' => 'PROD-' . $this->id . '-' . $size
                    ]
                );
            }
            
            // Remove tamanhos que não foram selecionados
            $this->variants()->whereNotIn('size', $sizes)->delete();
        }
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

    public function scopeInStock($query)
    {
        return $query->whereHas('variants', function ($query) {
            $query->where('stock_quantity', '>', 0)->where('is_available', true);
        });
    }

    public function hasStock($variantId = null, $quantity = 1)
    {
        if ($variantId) {
            return $this->variants()
                ->where('id', $variantId)
                ->where('stock_quantity', '>=', $quantity)
                ->where('is_available', true)
                ->exists();
        }

        return $this->total_stock >= $quantity;
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

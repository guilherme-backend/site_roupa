<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::with([
            'category',
            'images' => function ($query) {
                $query->orderBy('order');
            },
            'variants' => function ($query) {
                $query->where('is_available', true)
                    ->where('stock_quantity', '>', 0);
            }
        ])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

        // Produtos relacionados da mesma categoria
        $relatedProducts = Product::with(['primaryImage', 'category', 'variants'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // Agrupar variações por tamanho e cor
        $sizes = $product->variants->pluck('size')->unique()->values();
        $colors = $product->variants->map(function ($variant) {
            return [
                'name' => $variant->color,
                'hex' => $variant->color_hex,
            ];
        })->unique('name')->values();

        return view('products.show', compact('product', 'relatedProducts', 'sizes', 'colors'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        // 1. Busca Segura: Pega apenas produtos ativos e pagina (12 por página)
        // O select reduz o uso de memória drasticamente
        $products = Product::query()
            ->where('is_active', true)
            ->inStock() // Filtra apenas produtos com estoque
            ->with(['primaryImage', 'category'])
            ->latest()
            ->paginate(12);

        // 2. Categorias simples para o menu lateral (sem contagem pesada por enquanto)
        $categories = Category::query()
            ->select(['id', 'name', 'slug'])
            ->where('is_active', true)
            ->get();

        // Retorna a view que você já limpou
        return view('shop.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        // Busca do produto individual otimizada
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['primaryImage', 'images', 'category', 'variants'])
            ->firstOrFail();

        // Busca produtos relacionados simples (limite de 4)
        $relatedProducts = Product::query()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->inStock()
            ->with(['primaryImage', 'category'])
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }
    
    // Método auxiliar para filtro de categoria (caso você use a rota shop.category)
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->inStock()
            ->with(['primaryImage', 'category'])
            ->paginate(12);
            
        $categories = Category::select(['id', 'name', 'slug'])->where('is_active', true)->get();

        return view('shop.index', compact('products', 'categories', 'category'));
    }
}
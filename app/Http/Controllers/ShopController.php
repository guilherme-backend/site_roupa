<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Exibe a lista de produtos (Home da Loja)
     */
    public function index(Request $request)
    {
        $query = Product::with(['primaryImage', 'category', 'variants'])
            ->where('is_active', true);

        // Filtro por categoria (se vier via GET ?category=ID)
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Busca por nome
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Ordenação
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_asc':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('base_price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12);
        
        $categories = Category::where('is_active', true)
            ->withCount('activeProducts')
            ->orderBy('order')
            ->get();

        return view('shop.index', compact('products', 'categories'));
    }

    /**
     * Exibe os produtos de uma categoria específica (via URL /categoria/slug)
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $products = Product::with(['primaryImage', 'category', 'variants'])
            ->where('is_active', true)
            ->where('category_id', $category->id)
            ->latest()
            ->paginate(12);

        $categories = Category::where('is_active', true)
            ->withCount('activeProducts')
            ->orderBy('order')
            ->get();

        return view('shop.category', compact('category', 'products', 'categories'));
    }

    /**
     * Exibe os detalhes de UM produto (O método que faltava!)
     */
    public function show($slug)
    {
        // 1. Busca o produto pelo Slug
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            // CORREÇÃO: Removi 'brand' da lista abaixo para evitar o erro
            ->with(['images', 'variants', 'category']) 
            ->firstOrFail();

        // 2. Busca produtos relacionados (mesma categoria)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with('primaryImage') // Carrega apenas a imagem principal
            ->take(4)
            ->get();

        return view('shop.show', compact('product', 'relatedProducts'));
    }
}
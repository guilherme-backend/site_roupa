<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['primaryImage', 'category', 'variants'])
            ->where('is_active', true)
            ->where('is_featured', true)
            ->take(8)
            ->get();

        $newProducts = Product::with(['primaryImage', 'category', 'variants'])
            ->where('is_active', true)
            ->latest()
            ->take(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->withCount('activeProducts')
            ->orderBy('order')
            ->get();

        return view('home', compact('featuredProducts', 'newProducts', 'categories'));
    }
}

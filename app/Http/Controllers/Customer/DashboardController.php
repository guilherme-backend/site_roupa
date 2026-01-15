<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        $recentOrders = $user->orders()
            ->with(['items.product', 'items.productVariant'])
            ->latest()
            ->take(5)
            ->get();
        
        $stats = [
            'total_orders' => $user->orders()->count(),
            'pending_orders' => $user->orders()->where('status', 'pending_payment')->count(),
            'completed_orders' => $user->orders()->where('status', 'delivered')->count(),
            'total_spent' => $user->orders()->where('status', '!=', 'cancelled')->sum('total'),
        ];

        return view('customer.dashboard', compact('recentOrders', 'stats'));
    }
}

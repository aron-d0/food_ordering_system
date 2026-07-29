<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        return view('admin.dashboard', [
            'foodCount' => Food::count(),
            'availableFoodCount' => Food::available()->count(),
            'orderCount' => Order::count(),
            'pendingOrderCount' => Order::where('status', 'Pending')->count(),
            'customerCount' => User::where('role', 'customer')->count(),
            'recentOrders' => Order::with(['user', 'food'])->latest()->limit(6)->get(),
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'food'])->latest('order_date')->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(Order::STATUSES)],
        ]);

        $order->update($validated);

        return back()->with('success', 'Order status updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return back()->with('success', 'Order deleted successfully.');
    }
}

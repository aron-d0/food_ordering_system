<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function menu(Request $request)
    {
        $foods = Food::available()
            ->when($request->category, fn ($query) => $query->where('category', $request->category))
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        $categories = Food::available()->select('category')->distinct()->orderBy('category')->pluck('category');
        $cartRows = $this->cartRows();

        return view('customer.menu', [
            'foods' => $foods,
            'categories' => $categories,
            'cartRows' => $cartRows,
            'cartTotal' => $cartRows->sum('subtotal'),
            'cartCount' => collect($this->cartItems())->sum(),
        ]);
    }

    public function show(Food $food)
    {
        abort_unless($food->is_available && $food->stock_quantity > 0, 404);

        $cartRows = $this->cartRows();

        return view('customer.show', [
            'food' => $food,
            'cartRows' => $cartRows,
            'cartTotal' => $cartRows->sum('subtotal'),
        ]);
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'food_id' => ['required', 'integer', 'exists:foods,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $food = Food::available()->findOrFail($validated['food_id']);

        if ($validated['quantity'] > $food->stock_quantity) {
            return back()->withErrors([
                'quantity' => "Only {$food->stock_quantity} serving(s) are available.",
            ]);
        }

        $cart = $this->cartItems();
        $currentQuantity = $cart[$food->id] ?? 0;
        $newQuantity = min($food->stock_quantity, $currentQuantity + $validated['quantity']);

        $cart[$food->id] = $newQuantity;
        session(['cart' => $cart]);

        return redirect()->route('customer.menu', ['category' => $food->category])->with('success', "{$food->name} added to cart.");
    }

    public function cart()
    {
        $cartRows = $this->cartRows();

        return view('customer.cart', [
            'cartRows' => $cartRows,
            'cartTotal' => $cartRows->sum('subtotal'),
        ]);
    }

    public function updateCart(Request $request, Food $food)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1', 'max:'.$food->stock_quantity],
        ]);

        $cart = $this->cartItems();
        $cart[$food->id] = $validated['quantity'];
        session(['cart' => $cart]);

        return back()->with('success', 'Cart updated.');
    }

    public function removeFromCart(Food $food)
    {
        $cart = $this->cartItems();
        unset($cart[$food->id]);
        session(['cart' => $cart]);

        return back()->with('success', "{$food->name} removed from cart.");
    }

    public function clearCart()
    {
        session()->forget('cart');

        return redirect()->route('customer.menu')->with('success', 'Cart cleared.');
    }

    public function checkout()
    {
        $cartRows = $this->cartRows();

        if ($cartRows->isEmpty()) {
            return redirect()->route('customer.cart.index')->with('error', 'Your cart is empty.');
        }

        return view('customer.checkout', [
            'cartRows' => $cartRows,
            'cartTotal' => $cartRows->sum('subtotal'),
        ]);
    }

    public function placeOrder(Request $request)
    {
        $cart = $this->cartItems();

        if ($cart === []) {
            return redirect()->route('customer.cart.index')->with('error', 'Your cart is empty.');
        }

        $orders = DB::transaction(function () use ($cart) {
            $createdOrders = collect();

            foreach ($cart as $foodId => $quantity) {
                $food = Food::whereKey($foodId)->lockForUpdate()->firstOrFail();

                if (! $food->is_available || $food->stock_quantity < $quantity) {
                    abort(422, "{$food->name} is unavailable or does not have enough stock.");
                }

                $createdOrders->push(Order::create([
                    'user_id' => auth()->id(),
                    'food_id' => $food->id,
                    'quantity' => $quantity,
                    'total_price' => $food->price * $quantity,
                    'status' => 'Pending',
                    'order_date' => now(),
                ]));

                $food->decrement('stock_quantity', $quantity);
            }

            return $createdOrders;
        });

        session()->forget('cart');
        session(['last_order_ids' => $orders->pluck('id')->all()]);

        return redirect()->route('customer.order.success');
    }

    public function success()
    {
        $orders = Order::with('food')
            ->where('user_id', auth()->id())
            ->whereIn('id', session('last_order_ids', []))
            ->latest('order_date')
            ->get();

        return view('customer.success', [
            'orders' => $orders,
            'orderTotal' => $orders->sum('total_price'),
            'orderNumber' => $orders->isNotEmpty() ? str_pad((string) $orders->max('id'), 5, '0', STR_PAD_LEFT) : null,
        ]);
    }

    public function orders()
    {
        $orders = auth()->user()->orders()->with('food')->latest('order_date')->get();

        return view('customer.orders', compact('orders'));
    }

    private function cartItems(): array
    {
        return collect(session('cart', []))
            ->mapWithKeys(fn ($quantity, $foodId) => [(int) $foodId => (int) $quantity])
            ->filter(fn ($quantity) => $quantity > 0)
            ->all();
    }

    private function cartRows()
    {
        $cart = $this->cartItems();

        if ($cart === []) {
            return collect();
        }

        return Food::whereIn('id', array_keys($cart))
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->map(function (Food $food) use ($cart) {
                $quantity = min($cart[$food->id], $food->stock_quantity);

                return (object) [
                    'food' => $food,
                    'quantity' => $quantity,
                    'subtotal' => $food->price * $quantity,
                    'has_stock_issue' => ! $food->is_available || $food->stock_quantity < $cart[$food->id],
                ];
            });
    }
}

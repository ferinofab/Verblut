<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function checkout()
    {
        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Корзина пуста');
        }

        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $user = Auth::user();

        return view('order.checkout', compact('cart', 'total', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'address' => 'required|string',
            'city' => 'nullable|string',
            'postal_code' => 'nullable|string',
            'delivery_method' => 'required|in:courier,pickup,post',
            'payment_method' => 'required|in:card,cash',
            'comment' => 'nullable|string',
        ]);

        $cart = Cart::with('items.product')->where('user_id', Auth::id())->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Корзина пуста');
        }

        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->amount) {
                return back()->with('error', "Товар '{$item->product->name}' доступен в количестве {$item->product->amount} шт.");
            }
        }

        DB::beginTransaction();

        try {
            foreach ($cart->items as $item) {
                $product = $item->product;
                $product->amount -= $item->quantity;
                $product->save();
            }

            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $cart->items->sum(fn($i) => $i->quantity * $i->price),
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'delivery_method' => $request->delivery_method,
                'payment_method' => $request->payment_method,
                'comment' => $request->comment,
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_price' => $item->price,
                    'quantity' => $item->quantity,
                    'total' => $item->quantity * $item->price,
                ]);
            }

            $cart->items()->delete();

            DB::commit();

            return redirect()->route('order.success', $order)->with('success', 'Заказ успешно оформлен!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Ошибка при оформлении заказа: ' . $e->getMessage());
        }
    }

    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('order.success', compact('order'));
    }

    public function history()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('status')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('order.history', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product', 'status');

        return view('order.show', compact('order'));
    }
}

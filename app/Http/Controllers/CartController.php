<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function getCart()
    {
        return Cart::firstOrCreate(
            ['user_id' => Auth::id()]
        );
    }

    public function index()
    {
        $cart = $this->getCart();
        $cart->load('items.product');

        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return view('cart.index', compact('cart', 'total'));
    }

    public function add($productId)
    {
        $product = Product::findOrFail($productId);
        $cart = $this->getCart();

        if ($product->amount <= 0) {
            return back()->with('error', 'Товар отсутствует на складе');
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + 1;

            if ($newQuantity > $product->amount) {
                return back()->with('error', 'Невозможно добавить больше, чем есть на складе');
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => 1,
                'price' => $product->price,
            ]);
        }

        return back()->with('success', 'Товар добавлен в корзину');
    }

    public function addAjax(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $cart = $this->getCart();
        $quantity = $request->input('quantity', 1);

        if ($product->amount <= 0) {
            return response()->json(['error' => 'Товар отсутствует на складе'], 422);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $quantity;
            if ($newQuantity > $product->amount) {
                return response()->json(['error' => 'Невозможно добавить больше, чем есть на складе'], 422);
            }
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        $cartCount = $cart->items->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Товар добавлен в корзину',
            'cart_count' => $cartCount
        ]);
    }

    public function buyNow(Request $request, $productId)
    {
        if (!$request->ajax()) {
            $product = Product::findOrFail($productId);
            $quantity = $request->input('quantity', 1);

            if ($product->amount <= 0 || $quantity > $product->amount) {
                return redirect()->back()->with('error', 'Товар отсутствует в нужном количестве');
            }

            $cart = $this->getCart();
            $cart->items()->delete();

            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);

            return redirect()->route('checkout');
        }

        $product = Product::findOrFail($productId);
        $quantity = $request->input('quantity', 1);

        if ($product->amount <= 0 || $quantity > $product->amount) {
            if ($request->ajax()) {
                return response()->json(['error' => 'Товар отсутствует в нужном количестве'], 422);
            }
            return redirect()->back()->with('error', 'Товар отсутствует в нужном количестве');
        }

        $cart = $this->getCart();
        $cart->items()->delete();

        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $product->price,
        ]);

        if ($request->ajax()) {
            return response()->json(['redirect' => route('checkout')]);
        }

        return redirect()->route('checkout');
    }

    public function update($itemId, $quantity)
    {
        $cartItem = CartItem::findOrFail($itemId);

        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $product = Product::findOrFail($cartItem->product_id);

        if ($quantity <= 0) {
            $cartItem->delete();
        } else {
            if ($quantity > $product->amount) {
                return response()->json(['error' => 'Невозможно заказать больше, чем есть на складе'], 422);
            }
            $cartItem->update(['quantity' => $quantity]);
        }

        $cart = $cartItem->cart;
        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return response()->json([
            'success' => true,
            'item_total' => ($cartItem->quantity ?? 0) * ($cartItem->price ?? 0),
            'cart_total' => $total,
            'item_count' => $cart->items->count()
        ]);
    }

    public function remove($itemId)
    {
        $cartItem = CartItem::findOrFail($itemId);

        if ($cartItem->cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return response()->json(['success' => true]);
    }

    public function clear()
    {
        $cart = $this->getCart();
        $cart->items()->delete();

        return redirect()->route('cart')->with('success', 'Корзина очищена');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatus;
use App\Services\MoySkladService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        Log::info('🛒 Starting order creation', ['user_id' => Auth::id()]);

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

        // Проверяем наличие товаров
        foreach ($cart->items as $item) {
            if ($item->quantity > $item->product->amount) {
                return back()->with('error', "Товар '{$item->product->name}' доступен в количестве {$item->product->amount} шт.");
            }
        }

        DB::beginTransaction();

        try {
            $stockUpdates = [];

            // Уменьшаем остатки в локальной БД
            foreach ($cart->items as $item) {
                $product = $item->product;
                $newAmount = $product->amount - $item->quantity;
                $product->amount = $newAmount;
                $product->save();

                Log::info('📝 Updated local stock', [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'new_amount' => $newAmount,
                    'moysklad_id' => $product->moysklad_id ?? 'null'
                ]);

                if ($product->moysklad_id) {
                    $stockUpdates[$product->moysklad_id] = $newAmount;
                }
            }

            // Создаём заказ
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

            Log::info('📋 Order created', ['order_id' => $order->id]);

            // Создаём позиции заказа
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

            // Очищаем корзину
            $cart->items()->delete();

            // ОБНОВЛЯЕМ ОСТАТКИ В МОЙСКЛАД
            if (!empty($stockUpdates)) {
                Log::info('🔄 Updating stock in MoySklad...');

                $moySkladService = new MoySkladService();
                $result = $moySkladService->updateStocksBatch($stockUpdates);

                Log::info('📊 MoySklad update result', [
                    'success_count' => $result['success'],
                    'failed' => $result['failed']
                ]);

                if (!empty($result['failed'])) {
                    Log::warning('⚠️ Failed to update stock in MoySklad for products: ' . implode(', ', $result['failed']));
                }
            }

            DB::commit();

            Log::info('✅ Order completed successfully', ['order_id' => $order->id]);

            return redirect()->route('order.success', $order)->with('success', 'Заказ успешно оформлен!');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('❌ Order creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

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

    /**
     * Отображение всех заказов для администратора
     */
    public function adminIndex(Request $request)
    {
        $query = Order::with(['user', 'status', 'items']);

        // Фильтрация по статусу
        if ($request->has('status') && $request->status != '') {
            $query->where('order_status_id', $request->status);
        }

        // Поиск по номеру заказа, имени, телефону или email
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        // Получаем статистику по всем заказам (без фильтров)
        $allOrders = Order::all();
        $statistics = [
            'total' => $allOrders->count(),
            'new' => $allOrders->where('order_status_id', 1)->count(),
            'processing' => $allOrders->where('order_status_id', 2)->count(),
            'completed' => $allOrders->where('order_status_id', 4)->count(),
            'cancelled' => $allOrders->where('order_status_id', 5)->count(),
        ];

        $statuses = OrderStatus::all();

        return view('admin.orders.index', compact('orders', 'statuses', 'statistics'));
    }

    /**
     * Изменение статуса заказа
     */
    public function adminUpdateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status_id' => 'required|exists:order_statuses,id'
        ]);

        try {
            $oldStatus = $order->status->name ?? 'Неизвестно';
            $newStatus = OrderStatus::find($request->status_id);

            $order->status_id = $request->status_id;
            $order->save();

            Log::info('📝 Статус заказа изменён администратором', [
                'order_id' => $order->id,
                'admin_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus->name
            ]);

            // Для AJAX запроса возвращаем JSON
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Статус заказа #{$order->id} изменён на '{$newStatus->name}'",
                    'order' => $order,
                    'new_status' => $newStatus->name
                ]);
            }

            return redirect()->back()->with('success', "Статус заказа #{$order->id} изменён на '{$newStatus->name}'");

        } catch (\Exception $e) {
            Log::error('❌ Ошибка при изменении статуса заказа', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ошибка при изменении статуса заказа: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Ошибка при изменении статуса заказа');
        }
    }

    public function adminShow(Order $order)
    {
        $order->load(['user', 'items.product', 'status']);
        $statuses = OrderStatus::all();

        return view('admin.orders.show', compact('order', 'statuses'));
    }
}

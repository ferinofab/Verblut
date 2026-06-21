<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MoySkladService;

class SyncController extends Controller
{
    public function sync(MoySkladService $service)
    {
        if (!Auth()->user()->is_admin??null) {
            abort(403);
        }

        $products = $service->syncProducts();
        $stocks = $service->syncStocks();

        return back()->with('success', "Синхронизация завершена: товаров - {$products}, остатков - {$stocks}");
    }
}

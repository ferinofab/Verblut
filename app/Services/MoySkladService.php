<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoySkladService
{
    protected $login;
    protected $password;
    protected $apiUrl = 'https://api.moysklad.ru/api/remap/1.2/';
    protected $limit = 100; // Максимальное количество записей за запрос

    public function __construct()
    {
        $this->login = env('MOYSKLAD_LOGIN');
        $this->password = env('MOYSKLAD_PASSWORD');
    }

    /**
     * Синхронизация товаров с пагинацией
     */
    public function syncProducts()
    {
        $offset = 0;
        $totalCount = 0;
        $syncedCount = 0;

        try {
            do {
                $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($this->login . ':' . $this->password),
                    'Accept-Encoding' => 'gzip',
                    'Content-Type' => 'application/json',
                ])->withOptions([
                    'verify' => false,
                    'decode_content' => true,
                    'timeout' => 60,
                ])->get($this->apiUrl . 'entity/product', [
                    'limit' => $this->limit,
                    'offset' => $offset,
                ]);

                if (!$response->successful()) {
                    Log::error('MoySklad API error: ' . $response->status());
                    Log::error('MoySklad API Response Body: ' . $response->body());
                    break;
                }

                $data = $response->json();
                $products = $data['rows'] ?? [];
                $totalCount = $data['meta']['total'] ?? 0;

                foreach ($products as $item) {
                    $price = ($item['salePrices'][0]['value'] ?? 0) / 100;

                    Product::updateOrCreate(
                        ['moysklad_id' => $item['id']],
                        [
                            'name' => $item['name'],
                            'sku' => $item['code'] ?? null,
                            'price' => $price,
                            'description' => $item['description'] ?? '',
                            'amount' => $item['quantity'] ?? 0,
                        ]
                    );
                    $syncedCount++;
                }

                $offset += $this->limit;
                Log::info("Fetched " . count($products) . " products, total: {$totalCount}, offset: {$offset}");

            } while ($offset < $totalCount);

            Log::info('Synced ' . $syncedCount . ' products');
            return $syncedCount;

        } catch (\Exception $e) {
            Log::error('MoySklad syncProducts error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Синхронизация остатков с пагинацией
     */
    public function syncStocks()
    {
        $offset = 0;
        $totalCount = 0;
        $updatedCount = 0;

        try {
            do {
                $response = Http::withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($this->login . ':' . $this->password),
                    'Accept-Encoding' => 'gzip',
                ])->withOptions([
                    'verify' => false,
                    'decode_content' => true,
                    'timeout' => 60,
                ])->get($this->apiUrl . 'report/stock/all', [
                    'limit' => $this->limit,
                    'offset' => $offset,
                ]);

                if (!$response->successful()) {
                    Log::error('MoySklad API error: ' . $response->status() . ' - ' . $response->body());
                    break;
                }

                $data = $response->json();
                $rows = $data['rows'] ?? [];
                $totalCount = $data['meta']['total'] ?? 0;

                if (empty($rows)) {
                    break;
                }

                // Подготавливаем данные для массового обновления
                $stockData = [];
                foreach ($rows as $row) {
                    $href = $row['meta']['href'] ?? '';
                    if (!$href) continue;

                    $moyskladId = basename(parse_url($href, PHP_URL_PATH));
                    $stock = $row['stock'] ?? 0;

                    $stockData[$moyskladId] = $stock;
                }

                // Получаем все товары из БД одним запросом
                $products = Product::whereIn('moysklad_id', array_keys($stockData))
                    ->get(['id', 'moysklad_id', 'amount'])
                    ->keyBy('moysklad_id');

                foreach ($stockData as $moyskladId => $newStock) {
                    $product = $products->get($moyskladId);

                    // Если товара нет в БД, создаем его
                    if (!$product) {
                        // Создаем товар из данных остатков
                        $product = $this->createProductFromStock($moyskladId);
                        if (!$product) {
                            continue;
                        }
                    }

                    // Обновляем только если есть изменения
                    if ($product->amount != $newStock) {
                        $product->update(['amount' => $newStock]);
                        $updatedCount++;
                    }
                }

                $offset += $this->limit;
                Log::info("Fetched " . count($rows) . " stock records, total: {$totalCount}, offset: {$offset}");

            } while ($offset < $totalCount);

            Log::info("Synced {$updatedCount} products stock");
            return $updatedCount;

        } catch (\Exception $e) {
            Log::error('MoySklad syncStocks error: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Создание товара из данных об остатках
     */
    protected function createProductFromStock($moyskladId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->login . ':' . $this->password),
                'Accept-Encoding' => 'gzip',
            ])->withOptions([
                'verify' => false,
                'decode_content' => true,
                'timeout' => 30,
            ])->get($this->apiUrl . "entity/product/{$moyskladId}");

            if ($response->successful()) {
                $item = $response->json();
                $price = ($item['salePrices'][0]['value'] ?? 0) / 100;

                return Product::create([
                    'moysklad_id' => $item['id'],
                    'name' => $item['name'],
                    'sku' => $item['code'] ?? null,
                    'price' => $price,
                    'description' => $item['description'] ?? '',
                    'amount' => 0, // Будет обновлено в основном цикле
                ]);
            } else {
                Log::warning("Could not fetch product {$moyskladId} from API");
            }
        } catch (\Exception $e) {
            Log::error("Failed to create product from stock: {$moyskladId}", ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Полная синхронизация (товары + остатки)
     */
    public function fullSync()
    {
        $startTime = microtime(true);

        $productsCount = $this->syncProducts();
        $stocksCount = $this->syncStocks();

        $duration = round(microtime(true) - $startTime, 2);

        Log::info("Full sync completed in {$duration} seconds", [
            'products' => $productsCount,
            'stocks' => $stocksCount
        ]);

        return [
            'products' => $productsCount,
            'stocks' => $stocksCount,
            'duration' => $duration
        ];
    }

    /**
     * ОБНОВЛЕНИЕ ОСТАТКА В МОЙСКЛАД (ДОБАВЛЕНО)
     */
    /**
     * ОБНОВЛЕНИЕ ОСТАТКА ЧЕРЕЗ ДОКУМЕНТ РАСХОДА
     */
    public function updateStock($moyskladId, $newQuantity)
    {
        Log::info('🔄 Updating stock via Demand document', [
            'moysklad_id' => $moyskladId,
            'new_quantity' => $newQuantity
        ]);

        try {
            // 1. Получаем текущий товар
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->login . ':' . $this->password),
                'Accept-Encoding' => 'gzip',
            ])->withOptions([
                'verify' => false,
                'decode_content' => true,
                'timeout' => 30,
            ])->get($this->apiUrl . "entity/product/{$moyskladId}");

            if (!$response->successful()) {
                Log::error('❌ Failed to get product', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return false;
            }

            $product = $response->json();

            // 2. Получаем текущий остаток (из отчета)
            $stockResponse = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->login . ':' . $this->password),
                'Accept-Encoding' => 'gzip',
            ])->withOptions([
                'verify' => false,
                'decode_content' => true,
                'timeout' => 30,
            ])->get($this->apiUrl . "report/stock/all", [
                'filter' => "product.id={$moyskladId}",
                'limit' => 1
            ]);

            $currentStock = 0;
            if ($stockResponse->successful()) {
                $stockData = $stockResponse->json();
                $currentStock = $stockData['rows'][0]['stock'] ?? 0;
            }

            $quantityToWriteOff = $currentStock - $newQuantity;

            if ($quantityToWriteOff <= 0) {
                Log::info('No need to update stock', [
                    'current' => $currentStock,
                    'new' => $newQuantity
                ]);
                return true;
            }

            // 3. Получаем организацию и склад
            $orgResponse = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->login . ':' . $this->password),
            ])->withOptions([
                'verify' => false,
            ])->get($this->apiUrl . "entity/organization");

            $organization = $orgResponse->json()['rows'][0] ?? null;

            $storeResponse = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->login . ':' . $this->password),
            ])->withOptions([
                'verify' => false,
            ])->get($this->apiUrl . "entity/store");

            $store = $storeResponse->json()['rows'][0] ?? null;

            if (!$organization || !$store) {
                Log::error('❌ Failed to get organization or store');
                return false;
            }

            // 4. Создаем документ "Расход" (Demand)
            $demandData = [
                'name' => 'Списание по заказу #' . time(),
                'organization' => [
                    'meta' => $organization['meta']
                ],
                'store' => [
                    'meta' => $store['meta']
                ],
                'positions' => [
                    [
                        'quantity' => $quantityToWriteOff,
                        'price' => 0,
                        'assortment' => [
                            'meta' => $product['meta']
                        ]
                    ]
                ]
            ];

            Log::info('📤 Creating Demand document', [
                'data' => $demandData
            ]);

            $demandResponse = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->login . ':' . $this->password),
                'Content-Type' => 'application/json',
            ])->withOptions([
                'verify' => false,
                'decode_content' => true,
                'timeout' => 30,
            ])->post($this->apiUrl . "entity/demand", $demandData);

            if (!$demandResponse->successful()) {
                Log::error('❌ Failed to create Demand document', [
                    'status' => $demandResponse->status(),
                    'body' => $demandResponse->body()
                ]);
                return false;
            }

            Log::info('✅ Stock updated via Demand document', [
                'moysklad_id' => $moyskladId,
                'quantity_written_off' => $quantityToWriteOff
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('❌ Exception in updateStock', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Массовое обновление остатков для заказа (ДОБАВЛЕНО)
     */
    public function updateStocksBatch(array $updates)
    {
        $successCount = 0;
        $failedItems = [];

        foreach ($updates as $moyskladId => $newQuantity) {
            if ($this->updateStock($moyskladId, $newQuantity)) {
                $successCount++;
            } else {
                $failedItems[] = $moyskladId;
            }
        }

        return [
            'success' => $successCount,
            'failed' => $failedItems
        ];
    }
}

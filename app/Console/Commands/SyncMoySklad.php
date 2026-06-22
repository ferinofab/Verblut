<?php

namespace App\Console\Commands;

use App\Services\MoySkladService;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Isolatable;

class SyncMoySklad extends Command implements Isolatable
{
    protected $signature = 'moysklad:sync
                            {--type=full : Тип синхронизации: full, products, stocks}';

    protected $description = 'Sync products and stocks from MoySklad';

    public function handle(MoySkladService $service)
    {
        $type = $this->option('type');

        $this->info('🚀 Starting MoySklad synchronization...');
        $this->line('Type: ' . $type);

        try {
            $startTime = microtime(true);

            switch ($type) {
                case 'products':
                    $this->info('📦 Syncing products...');
                    $count = $service->syncProducts();
                    $this->info("✅ Synced {$count} products");
                    break;

                case 'stocks':
                    $this->info('📊 Syncing stocks...');
                    $count = $service->syncStocks();
                    $this->info("✅ Synced {$count} stock records");
                    break;

                case 'full':
                default:
                    $this->info('📦 Syncing products...');
                    $productsCount = $service->syncProducts();
                    $this->info("✅ Synced {$productsCount} products");

                    $this->info('📊 Syncing stocks...');
                    $stocksCount = $service->syncStocks();
                    $this->info("✅ Synced {$stocksCount} stock records");
                    break;
            }

            $duration = round(microtime(true) - $startTime, 2);
            $this->info("⏱️  Completed in {$duration} seconds");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}

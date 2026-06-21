<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;

class ProductImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = [
            // product_id, image_path, is_main
            [1, '/storage/products/1/54248279a71d409ec9c7433166b3ca18.jpg', 1],
            [2, '/storage/products/2/5132ea6814d27c165ebcf7e0eb401737.jpg', 1],
            [2, '/storage/products/2/953ffa5a051986d579020c28db80f61b.jpg', 0],
            [3, '/storage/products/3/39a20914debdad3335b6e67240c47ed2.jpg', 1],
            [4, '/storage/products/4/1e7f604a9cb08f53b6178f56d53afeae.jpg', 1],
            [5, '/storage/products/5/6ce2dbf238b3c3491d1d166eeba2ff5c.jpg', 1],
            [6, '/storage/products/6/0LZVx7cZ4oc7skgGJZOpsHZ3tUhS1XTNOCmsoGSK.jpg', 1],
            [7, '/storage/products/7/eylHaQZFc9ja6vEWCUnYA4c2xalUs59riC34nszd.jpg', 1],
            [8, '/storage/products/8/vZJGesmkdQ1NWxhFLLD5YZjdvsnvRmu5X5lvwpHU.jpg', 1],
            [9, '/storage/products/9/4cfa505d33262ec3eac8208165531a76.jpg', 1],
            [10, '/storage/products/10/Og7PFSdOGOGKdlR6ah8YTrc1f6Vbce2caUtphBgK.jpg', 1],
        ];

        foreach ($images as $imageData) {
            [$productId, $imagePath, $isMain] = $imageData;

            // Проверяем, существует ли товар
            $product = Product::find($productId);

            if ($product) {
                // Обновляем или создаём изображение
                ProductImage::updateOrCreate(
                    [
                        'product_id' => $productId,
                        'image_path' => $imagePath,
                    ],
                    [
                        'is_main' => $isMain,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            } else {
                $this->command->warn("Товар с ID {$productId} не найден, изображение пропущено");
            }
        }

        $this->command->info('Загрузка изображений завершена!');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsAndCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Категории с external_code
        $categories = [
            // Основные категории (parent_id = null)
            ['id' => 1, 'name' => 'Полировальные круги', 'slug' => 'polirovalnye-krugi', 'parent_id' => null, 'external_code' => 'CAT_001'],
            ['id' => 2, 'name' => 'Химия для детейлинга', 'slug' => 'himiya-dlya-deteylinga', 'parent_id' => null, 'external_code' => 'CAT_002'],
            ['id' => 3, 'name' => 'Защитные покрытия', 'slug' => 'zashitnye-pokrytiya', 'parent_id' => null, 'external_code' => 'CAT_003'],
            ['id' => 4, 'name' => 'Освежители воздуха', 'slug' => 'osvezhiteli-vozduha', 'parent_id' => null, 'external_code' => 'CAT_004'],
            ['id' => 5, 'name' => 'Кисти и щетки', 'slug' => 'kisti-i-shetki', 'parent_id' => null, 'external_code' => 'CAT_005'],
            ['id' => 6, 'name' => 'Микрофибра и полотенца', 'slug' => 'mikrofibra-i-polotentsa', 'parent_id' => null, 'external_code' => 'CAT_006'],
            ['id' => 7, 'name' => 'Инструмент и оснастка', 'slug' => 'instrument-i-osnastka', 'parent_id' => null, 'external_code' => 'CAT_007'],
            ['id' => 8, 'name' => 'Ремонт и комплектующие', 'slug' => 'remont-i-komplektuyuschie', 'parent_id' => null, 'external_code' => 'CAT_008'],
            ['id' => 9, 'name' => 'Оборудование', 'slug' => 'oborudovanie', 'parent_id' => null, 'external_code' => 'CAT_009'],
            ['id' => 10, 'name' => 'Аксессуары и одежда', 'slug' => 'aksessuary-i-odezhda', 'parent_id' => null, 'external_code' => 'CAT_010'],

            // Подкатегории для Полировальные круги (id=1)
            ['id' => 11, 'name' => 'Полировальные круги для DA-машин', 'slug' => 'da-pads', 'parent_id' => 1, 'external_code' => 'CAT_011'],
            ['id' => 12, 'name' => 'Полировальные круги для роторных машин', 'slug' => 'rotary-pads', 'parent_id' => 1, 'external_code' => 'CAT_012'],
            ['id' => 13, 'name' => 'Мини-круги (до 100 мм)', 'slug' => 'mini-pads', 'parent_id' => 1, 'external_code' => 'CAT_013'],
            ['id' => 14, 'name' => 'Шерстяные круги', 'slug' => 'wool-pads', 'parent_id' => 1, 'external_code' => 'CAT_014'],

            // Подкатегории для Химия (id=2)
            ['id' => 15, 'name' => 'Шампуни для бесконтактной мойки', 'slug' => 'touchless-shampoos', 'parent_id' => 2, 'external_code' => 'CAT_015'],
            ['id' => 16, 'name' => 'Ручные автошампуни', 'slug' => 'hand-shampoos', 'parent_id' => 2, 'external_code' => 'CAT_016'],
            ['id' => 17, 'name' => 'Очистители и обезжириватели', 'slug' => 'cleaners-degreasers', 'parent_id' => 2, 'external_code' => 'CAT_017'],
            ['id' => 18, 'name' => 'Средства для ухода за интерьером', 'slug' => 'interior-care', 'parent_id' => 2, 'external_code' => 'CAT_018'],
            ['id' => 19, 'name' => 'Средства для ухода за кожей', 'slug' => 'leather-care', 'parent_id' => 2, 'external_code' => 'CAT_019'],
            ['id' => 20, 'name' => 'Полировальные пасты', 'slug' => 'polishing-pastes', 'parent_id' => 2, 'external_code' => 'CAT_020'],

            // Подкатегории для Защитные покрытия (id=3)
            ['id' => 21, 'name' => 'Керамические покрытия', 'slug' => 'ceramic-coatings', 'parent_id' => 3, 'external_code' => 'CAT_021'],
            ['id' => 22, 'name' => 'Кварцевые покрытия', 'slug' => 'quartz-coatings', 'parent_id' => 3, 'external_code' => 'CAT_022'],
            ['id' => 23, 'name' => 'Защита для стекол', 'slug' => 'glass-coatings', 'parent_id' => 3, 'external_code' => 'CAT_023'],
            ['id' => 24, 'name' => 'Защита для дисков', 'slug' => 'wheel-coatings', 'parent_id' => 3, 'external_code' => 'CAT_024'],
            ['id' => 25, 'name' => 'Защита для кожи', 'slug' => 'leather-coatings', 'parent_id' => 3, 'external_code' => 'CAT_025'],

            // Подкатегории для Освежители воздуха (id=4)
            ['id' => 26, 'name' => 'Little Joe', 'slug' => 'little-joe', 'parent_id' => 4, 'external_code' => 'CAT_026'],
            ['id' => 27, 'name' => 'Фруктовые ароматы', 'slug' => 'fruit-scents', 'parent_id' => 4, 'external_code' => 'CAT_027'],
            ['id' => 28, 'name' => 'Цветочные ароматы', 'slug' => 'flower-scents', 'parent_id' => 4, 'external_code' => 'CAT_028'],
            ['id' => 29, 'name' => 'Мятные ароматы', 'slug' => 'mint-scents', 'parent_id' => 4, 'external_code' => 'CAT_029'],

            // Подкатегории для Кисти и щетки (id=5)
            ['id' => 30, 'name' => 'Для чистки дисков', 'slug' => 'wheel-brushes', 'parent_id' => 5, 'external_code' => 'CAT_030'],
            ['id' => 31, 'name' => 'Для детейлинга (деликатные)', 'slug' => 'detailing-brushes', 'parent_id' => 5, 'external_code' => 'CAT_031'],
            ['id' => 32, 'name' => 'Щетки для салона', 'slug' => 'interior-brushes', 'parent_id' => 5, 'external_code' => 'CAT_032'],

            // Подкатегории для Микрофибра (id=6)
            ['id' => 33, 'name' => 'Для полировки', 'slug' => 'polishing-towels', 'parent_id' => 6, 'external_code' => 'CAT_033'],
            ['id' => 34, 'name' => 'Для сушки', 'slug' => 'drying-towels', 'parent_id' => 6, 'external_code' => 'CAT_034'],
            ['id' => 35, 'name' => 'Для стекол', 'slug' => 'glass-towels', 'parent_id' => 6, 'external_code' => 'CAT_035'],
            ['id' => 36, 'name' => 'Оверлоченные полотенца', 'slug' => 'edgeless-towels', 'parent_id' => 6, 'external_code' => 'CAT_036'],

            // Подкатегории для Инструмент (id=7)
            ['id' => 37, 'name' => 'Пистолеты и пеногенераторы', 'slug' => 'foam-guns', 'parent_id' => 7, 'external_code' => 'CAT_037'],
            ['id' => 38, 'name' => 'Аппликаторы и губки', 'slug' => 'applicators-sponges', 'parent_id' => 7, 'external_code' => 'CAT_038'],
            ['id' => 39, 'name' => 'Ведра и фильтры', 'slug' => 'buckets-filters', 'parent_id' => 7, 'external_code' => 'CAT_039'],
            ['id' => 40, 'name' => 'Бутылки и дозаторы', 'slug' => 'bottles-dispensers', 'parent_id' => 7, 'external_code' => 'CAT_040'],
            ['id' => 41, 'name' => 'Полировальная глина', 'slug' => 'clay-bars', 'parent_id' => 7, 'external_code' => 'CAT_041'],

            // Подкатегории для Ремонт (id=8)
            ['id' => 42, 'name' => 'Сальники и уплотнители', 'slug' => 'seals-gaskets', 'parent_id' => 8, 'external_code' => 'CAT_042'],
            ['id' => 43, 'name' => 'Фитинги и переходники', 'slug' => 'fittings-adapters', 'parent_id' => 8, 'external_code' => 'CAT_043'],
            ['id' => 44, 'name' => 'Шланги высокого давления', 'slug' => 'pressure-hoses', 'parent_id' => 8, 'external_code' => 'CAT_044'],
            ['id' => 45, 'name' => 'Поршни и комплектующие', 'slug' => 'pistons-parts', 'parent_id' => 8, 'external_code' => 'CAT_045'],

            // Подкатегории для Оборудование (id=9)
            ['id' => 46, 'name' => 'Турбосушки', 'slug' => 'car-dryers', 'parent_id' => 9, 'external_code' => 'CAT_046'],
            ['id' => 47, 'name' => 'Озонаторы', 'slug' => 'ozonators', 'parent_id' => 9, 'external_code' => 'CAT_047'],
            ['id' => 48, 'name' => 'Пылесосы', 'slug' => 'vacuums', 'parent_id' => 9, 'external_code' => 'CAT_048'],
            ['id' => 49, 'name' => 'Трансформаторы', 'slug' => 'transformers', 'parent_id' => 9, 'external_code' => 'CAT_049'],

            // Подкатегории для Аксессуары (id=10)
            ['id' => 50, 'name' => 'Одежда с логотипом', 'slug' => 'branded-clothing', 'parent_id' => 10, 'external_code' => 'CAT_050'],
            ['id' => 51, 'name' => 'Накидки на сиденья', 'slug' => 'seat-covers', 'parent_id' => 10, 'external_code' => 'CAT_051'],
            ['id' => 52, 'name' => 'Крючки и держатели', 'slug' => 'hooks-holders', 'parent_id' => 10, 'external_code' => 'CAT_052'],
        ];

        // Вставка категорий
        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['id' => $cat['id']],
                [
                    'name' => $cat['name'],
                    'slug' => $cat['slug'],
                    'parent_id' => $cat['parent_id'],
                    'external_code' => $cat['external_code'],
                ]
            );
        }

        // Данные товаров (используем поле amount вместо quantity)
        $products = [
            ['id' => 1, 'sku' => '63256355', 'name' => 'Shine Systems RO Foam Pad Green - полировальный круг твердый зеленый, 155 мм', 'amount' => 13, 'price' => 990, 'category_id' => 11, 'moysklad_id' => rand(100000, 999999)],
            ['id' => 2, 'sku' => '00301', 'name' => 'Shine Systems Detailing Brush N10 - деликатная кисть для детейлинга средняя', 'amount' => 193, 'price' => 190, 'category_id' => 31, 'moysklad_id' => rand(100000, 999999)],
            ['id' => 3, 'sku' => '00285', 'name' => 'SGCB Foam Pad set 3.2" Набор мини кружков для полировки 82 мм (4 шт)', 'amount' => 0, 'price' => 817, 'category_id' => 13, 'moysklad_id' => rand(100000, 999999)],
            ['id' => 4, 'sku' => '63256214', 'name' => 'Shine Systems BL InteriorDetailer Macadamia - средство для ухода за интерьером, 5 л', 'amount' => 12, 'price' => 1990, 'category_id' => 18, 'moysklad_id' => rand(100000, 999999)],
            ['id' => 5, 'sku' => '9900069172', 'name' => 'One Q2 Light (50 ml) кварцевое защитное покрытие (серия "Энтузиаст"), GYEON', 'amount' => 2, 'price' => 7190, 'category_id' => 22, 'moysklad_id' => rand(100000, 999999)],
            ['id' => 6, 'sku' => '6554254', 'name' => 'SONAX ProfiLine Leather Care - Лосьон для кожи, 1л', 'amount' => 0, 'price' => 3067, 'category_id' => 19, 'moysklad_id' => rand(100000, 999999)],
            ['id' => 7, 'sku' => '63255918', 'name' => 'Пистолет для пеногенератора 75 см (пласт. ручка)', 'amount' => 70, 'price' => 1990, 'category_id' => 37, 'moysklad_id' => rand(100000, 999999)],
            ['id' => 8, 'sku' => '63256091', 'name' => 'Керамическое покрытие Artdeshine BX Coating 1 мл', 'amount' => 0, 'price' => 1590, 'category_id' => 21, 'moysklad_id' => rand(100000, 999999)],
            ['id' => 9, 'sku' => '00345', 'name' => 'Shine Systems Coating Sponge - Аппликатор с прорезью для керамики 8,5*4,5*2,5 см', 'amount' => 2, 'price' => 90, 'category_id' => 38, 'moysklad_id' => rand(100000, 999999)],
            ['id' => 10, 'sku' => '63255647', 'name' => 'ZviZZer Полировальный круг белый шерстяной 165/15/165 мм (ворс 5мм)', 'amount' => 0, 'price' => 1546, 'category_id' => 14, 'moysklad_id' => rand(100000, 999999)],
        ];

        // Вставка товаров
        foreach ($products as $product) {
            Product::updateOrCreate(
                ['id' => $product['id']],
                [
                    'sku' => $product['sku'],
                    'name' => $product['name'],
                    'amount' => $product['amount'],  // Исправлено: amount вместо quantity
                    'price' => $product['price'],
                    'category_id' => $product['category_id'],
                ]
            );
        }
    }
}

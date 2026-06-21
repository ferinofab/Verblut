<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Очищаем таблицу перед заполнением (опционально)
        Schema::disableForeignKeyConstraints();
        DB::table('order_statuses')->truncate();
        Schema::enableForeignKeyConstraints();

        $statuses = [
            [
                'id' => 1,
                'name' => 'новый',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'оплачен',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'не оплачен',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'отклонен',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('order_statuses')->insert($statuses);
    }

}

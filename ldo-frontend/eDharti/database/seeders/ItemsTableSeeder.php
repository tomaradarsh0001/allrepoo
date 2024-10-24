<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ItemsTableSeeder extends Seeder
{
    public function run()
    {
        // Updating existing items
        $updates = [
            [
                'item_code' => 'PG_NEW',
                'item_name' => 'New',
                'item_order' => 1,
            ],
            [
                'item_code' => 'PG_INP',
                'item_name' => 'In Process',
                'item_order' => 3,
            ],
            [
                'item_code' => 'PG_CAN',
                'item_name' => 'Cancelled',
                'item_order' => 4,
            ],
            [
                'item_code' => 'PG_RES',
                'item_name' => 'Resolved',
                'item_order' => 5,
            ],
            [
                'item_code' => 'PG_REO',
                'item_name' => 'Re-Open',
                'item_order' => 6,
            ],
        ];

        foreach ($updates as $update) {
            DB::table('items')->updateOrInsert(
                ['item_code' => $update['item_code']], // Unique identifier
                [
                    'group_id' => 17004,
                    'item_name' => $update['item_name'],
                    'color_code' => null, // Assuming color_code to be null for updates
                    'item_order' => $update['item_order'],
                    'is_active' => 1,
                    'created_at' => Carbon::now(),  // Update this only if it is a new entry
                    'updated_at' => Carbon::now(),
                    'created_by' => null,
                    'updated_by' => null,
                ]
            );
        }

        // Inserting new item
        DB::table('items')->insert([
            'group_id' => 17004,
            'item_code' => 'PG_PEN',
            'item_name' => 'Pending',
            'color_code' => null,
            'item_order' => 2,
            'is_active' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'created_by' => null,
            'updated_by' => null,
        ]);
    }
}

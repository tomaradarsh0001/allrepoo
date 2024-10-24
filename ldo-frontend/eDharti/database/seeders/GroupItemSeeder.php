<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Item;

class GroupItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $items = [
            /* [
                'group_id' => 1031,
                'item_code' => 'APP_NEW',
                'item_name' => 'New Application',
            ],
            [
                'group_id' => 1031,
                'item_code' => 'APP_PEN',
                'item_name' => 'Pending',
            ],
            [
                'group_id' => 1031,
                'item_code' => 'APP_IP',
                'item_name' => 'In Progress',
            ],
            [
                'group_id' => 1031,
                'item_code' => 'APP_OBJ',
                'item_name' => 'Objected',
            ],
            [
                'group_id' => 1031,
                'item_code' => 'APP_APR',
                'item_name' => 'Approved',
            ],
            [
                'group_id' => 1031,
                'item_code' => 'APP_REJ',
                'item_name' => 'Rejected',
            ],
            [
                'group_id' => 1031,
                'item_code' => 'APP_CAN',
                'item_name' => 'Cancelled',
            ],
            [
                'group_id' => 1031,
                'item_code' => 'APP_WD',
                'item_name' => 'Withdrawn',
            ], */

            //for forgot password otps
            [
                'group_id' => 17002,
                'item_code' => 'PASS_FORGET',
                'item_name' => 'Forgot Password',
            ],

        ];

        $itemOder = 1;
        foreach ($items as $item) {
            DB::table('items')->updateOrInsert(
                [

                    'item_code' => $item['item_code'],
                    'group_id' => $item['group_id'],

                ],
                [
                    'item_name' => $item['item_name'],
                    'color_code' => null,
                    // 'item_order' => $itemOder++,
                    'is_active' => 1,
                    'created_at' => Carbon::now(),
                    'created_by' => null,
                    'updated_at' => Carbon::now(),
                    'updated_by' => null
                ]
            );
        }
    }
}

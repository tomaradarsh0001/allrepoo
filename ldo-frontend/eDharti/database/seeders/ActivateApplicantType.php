<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivateApplicantType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Item::where('group_id', 4001)->whereIn('item_code', ['1', '3'])->update(['is_active' => 1]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\ConversionCharge;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConversionChargesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['property_type' => 47, 'area_from' => 0, 'area_to' => 50, 'formula' => '0'],
            ['property_type' => 47, 'area_from' => 50, 'area_to' => 150, 'formula' => '0.075 * R * (P-50)'],
            ['property_type' => 47, 'area_from' => 150, 'area_to' => 250, 'formula' => '(7.5 * R) + (0.1 * R * (P-150))'],
            ['property_type' => 47, 'area_from' => 250, 'area_to' => 350, 'formula' => '(17.5 * R) + (0.15 * R * (P-250))'],
            ['property_type' => 47, 'area_from' => 350, 'area_to' => 500, 'formula' => '(32.5 * R) + (0.2 * R* (P-350))'],
            ['property_type' => 47, 'area_from' => 500, 'area_to' => 750, 'formula' => '(62.5 * R) + (0.25 * R* (P-500))'],
            ['property_type' => 47, 'area_from' => 750, 'area_to' => 1000, 'formula' => '(125 * R) + (0.3 * R* (P-750))'],
            ['property_type' => 47, 'area_from' => 1000, 'area_to' => 2000, 'formula' => '(200 * R) + (0.4 * R* (P-1000))'],
            ['property_type' => 47, 'area_from' => 2000, 'area_to' => null, 'formula' => '(600 * R) + (0.5 * R* (P-2000))'],
            ['property_type' => 48, 'area_from' => null, 'area_to' => null, 'formula' => 'P * R * 10/100'],
            ['property_type' => 49, 'area_from' => null, 'area_to' => null, 'formula' => 'P * R * 10/100'],
            ['property_type' => 73, 'area_from' => null, 'area_to' => null, 'formula' => 'P * R * 10/100'],
        ];
        ConversionCharge::insert($data);
    }
}

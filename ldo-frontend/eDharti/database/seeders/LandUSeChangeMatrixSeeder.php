<?php

namespace Database\Seeders;

use App\Models\LandUseChangeMatrix;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LandUSeChangeMatrixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $matrx = [
            [
                'from' => ['property_type' => 47, 'property_sub_type' => 17],
                'to' => [
                    ['property_type' => 47, 'property_sub_type' => 1355]
                ]
            ],
            [
                'from' => ['property_type' => 47, 'property_sub_type' => 1355],
                'to' => [
                    ['property_type' => 47, 'property_sub_type' => 17]
                ]
            ],
            [
                'from' => ['property_type' => 48, 'property_sub_type' => 17],
                'to' => [
                    ['property_type' => 47, 'property_sub_type' => 17],
                    ['property_type' => 47, 'property_sub_type' => 1355],
                    ['property_type' => 48, 'property_sub_type' => 1355],
                    ['property_type' => 469, 'property_sub_type' => 17],
                ]
            ],
            [
                'from' => ['property_type' => 48, 'property_sub_type' => 1355],
                'to' => [
                    ['property_type' => 47, 'property_sub_type' => 17],
                    ['property_type' => 47, 'property_sub_type' => 1355],
                    ['property_type' => 48, 'property_sub_type' => 17],
                    ['property_type' => 469, 'property_sub_type' => 17]
                ]
            ],
            [
                'from' => ['property_type' => 469, 'property_sub_type' => 17],
                'to' => [
                    ['property_type' => 47, 'property_sub_type' => 17],
                    ['property_type' => 47, 'property_sub_type' => 1355]
                ]
            ],
            [
                'from' => ['property_type' => 48, 'property_sub_type' => 403],
                'to' => [
                    ['property_type' => 47, 'property_sub_type' => 17],
                    ['property_type' => 47, 'property_sub_type' => 1355],
                    ['property_type' => 48, 'property_sub_type' => 17],
                    ['property_type' => 48, 'property_sub_type' => 1355],
                    ['property_type' => 469, 'property_sub_type' => 17],
                    ['property_type' => 48, 'property_sub_type' => 407]
                ]
            ],
            [
                'from' => ['property_type' => 48, 'property_sub_type' => 407],
                'to' => [
                    ['property_type' => 47, 'property_sub_type' => 17],
                    ['property_type' => 47, 'property_sub_type' => 1355],
                    ['property_type' => 48, 'property_sub_type' => 17],
                    ['property_type' => 48, 'property_sub_type' => 1355],
                    ['property_type' => 469, 'property_sub_type' => 17],
                    ['property_type' => 48, 'property_sub_type' => 403]
                ]
            ],
        ];

        $rate = 2;
        $date_from = date('Y-01-01');

        foreach ($matrx as $prop) {
            $from = $prop['from'];
            $to = $prop['to'];
            foreach ($to as $row) {
                LandUseChangeMatrix::insert([
                    'property_type_from' => $from['property_type'],
                    'property_sub_type_from' => $from['property_sub_type'],
                    'property_type_to' => $row['property_type'],
                    'property_sub_type_to' => $row['property_sub_type'],
                    'date_from' => $date_from,
                    'rate' => $rate
                ]);
            }
        }
    }
}

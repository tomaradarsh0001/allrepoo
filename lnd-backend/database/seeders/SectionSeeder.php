<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        //
        $data=[
            ['created_at'=> now(), 'created_by'=>'2','is_active' => '1', 'name_eng' =>'L&DO Office','name_hin' => 'एलएंडडीओ कार्यालय', 'short_name' => 'L&DO Office', 'sort_order'=>'1', 'updated_at'=>now(),'updated_by'=>'2'],
            ['created_at'=> now(), 'created_by'=>'2', 'is_active' => '1', 'name_eng' =>'Admin Section','name_hin' => 'प्रशासन खंड', 'short_name' => 'Admin', 'sort_order'=>'2', 'updated_at'=>now(),'updated_by'=>'2'],
            ['created_at'=> now(), 'created_by'=>'2', 'is_active' => '1', 'name_eng' =>'Property Section-I','name_hin' => 'संपत्ति खंड-I', 'short_name' => 'PS-I', 'sort_order'=>'3', 'updated_at'=>now(),'updated_by'=>'2'],
            ['created_at'=> now(), 'created_by'=>'2', 'is_active' => '1', 'name_eng' =>'Property Section-II','name_hin' => 'संपत्ति खंड-II', 'short_name' => 'PS-II', 'sort_order'=>'4','updated_at'=>now(),'updated_by'=>'2'],
            ['created_at'=> now(), 'created_by'=>'2', 'is_active' => '1', 'name_eng' =>'Property Section-III','name_hin' => 'संपत्ति खंड-III', 'short_name' => 'PS-III', 'sort_order'=>'5', 'updated_at'=>now(),'updated_by'=>'2'],
            ['created_at'=> now(), 'created_by'=>'2', 'is_active' => '1', 'name_eng' =>'Lease - I Section', 'name_hin' => 'लीज - I खंड', 'short_name' => 'L-I', 'sort_order'=>'6', 'updated_at'=>now(),'updated_by'=>'2'],
            ['created_at'=> now(), 'created_by'=>'2', 'is_active' => '1', 'name_eng' =>'Lease - II(A) Section', 'name_hin' => 'लीज - II(अ) खंड', 'short_name' => 'L-II(A)', 'sort_order'=>'7','updated_at'=>now(),'updated_by'=>'2'],
            ['created_at'=> now(), 'created_by'=>'2','is_active' => '1', 'name_eng' =>'Lease - II(B) Section','name_hin' => 'लीज - II(ब) खंड', 'short_name' => 'L-II(B)', 'sort_order'=>'8', 'updated_at'=>now(),'updated_by'=>'2'],
            ['created_at'=> now(), 'created_by'=>'2', 'is_active' => '1', 'name_eng' =>'Lease - IV Section','name_hin' => 'लीज - IV खंड', 'short_name' => 'L-IV', 'sort_order'=>'9', 'updated_at'=>now(),'updated_by'=>'2'],
            ['created_at'=> now(), 'created_by'=>'2', 'is_active' => '1', 'name_eng' =>'Lease - V Section','name_hin' => 'लीज - V खंड', 'short_name' => 'L-V', 'sort_order'=>'10', 'updated_at'=>now(),'updated_by'=>'2'],
            ['created_at'=> now(), 'created_by'=>'2', 'is_active' => '1', 'name_eng' =>'Vigilance Section','name_hin' => 'सतर्कता खंड', 'short_name' => 'Vigilance', 'sort_order'=>'11','updated_at'=>now(),'updated_by'=>'2'],
            ['created_at'=> now(), 'created_by'=>'2', 'is_active' => '1', 'name_eng' =>'IAC Section','name_hin' => 'आईएसी खंड', 'short_name' => 'IAC', 'sort_order'=>'12', 'updated_at'=>now(),'updated_by'=>'2'],
            ['created_at'=> now(), 'created_by'=>'2', 'is_active' => '1', 'name_eng' =>'RP Cell', 'name_hin' => 'आरपी सेल', 'short_name' => 'RP Cell', 'sort_order'=>'13', 'updated_at'=>now(),'updated_by'=>'2'],
            ['created_at'=> now(), 'created_by'=>'2', 'is_active' => '1', 'name_eng' =>'Technical Section', 'name_hin' => 'तकनीकी खंड', 'short_name' => 'Technical', 'sort_order'=>'14','updated_at'=>now(),'updated_by'=>'2'],
            ['created_at'=> now(), 'created_by'=>'2', 'is_active' => '1', 'name_eng' =>'Accounts Section', 'name_hin' => 'लेखा खंड', 'short_name' => 'Account', 'sort_order'=>'14','updated_at'=>now(),'updated_by'=>'2'],
        ];

        DB::table('sections')->insert($data);
    }
}

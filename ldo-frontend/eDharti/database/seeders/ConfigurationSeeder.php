<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigurationSeeder extends Seeder
{
    public function run()
    {
        DB::table('configurations')->insert([
            [
                'key' => 'ACd16780a5969099bb32e30368a65879da',
                'auth_token' => '2ac6625206a2bc14bea0e9b2c59c1e2a',
                'api' => null,
                'sms_number' => '+19152065446',
                'whatsapp_number' => 'whatsapp:+14155238886',
                'email' => 'nitinrag@gmail.com',
                'port' => 587,
                'host' => 'smtp.gmail.com',
                'encryption' => null,
                'created_by' => null,                
                'updated_by' => null,                
            ],
            
        ]);
    }
}

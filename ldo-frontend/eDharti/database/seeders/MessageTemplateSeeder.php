<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Template;


class MessageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Template::insert([
            [
                'action' => 'OtpValidate',
                'type' => 'sms',
                'template' => 'Hi, Your OTP is {otp}, for verification of Mobile number on eDharti.',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'action' => 'OtpValidate',
                'type' => 'whatsapp',
                'template' => 'Hi, Your OTP is {otp}, for verification of WhatsApp number on eDharti.',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'action' => 'OtpValidate',
                'type' => 'email',
                'template' => 'Hi, Your OTP is {otp}, for verification of Email on eDharti.',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'action' => 'RegistrationSuccess',
                'type' => 'sms',
                'template' => 'Congratulations {name}. You are Successfully Registered in eDharti. Use your Email {email} or Registration ID {regNo} for login in eDharti Portal!',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'action' => 'RegistrationSuccess',
                'type' => 'whatsapp',
                'template' => 'Congratulations {name}. You are Successfully Registered in eDharti. Use your Email {email} or Registration ID {regNo} for login in eDharti Portal!',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
            [
                'action' => 'RegistrationSuccess',
                'type' => 'email',
                'template' => 'Congratulations {name}. You are Successfully Registered in eDharti. Use your Email {email} or Registration ID {regNo} for login in eDharti Portal!',
                'status' => 1,
                'created_by' => 1,
                'updated_by' => 1,
            ],
        ]);
    }
}
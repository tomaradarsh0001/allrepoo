<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class SectionUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $users = [
            [
                'name' => 'Dinesh Kumar Lakhumna',
                'email' => 'dinesh@yopmail.com',
                'mobile_no' => '9987767234',
                'role' => 'deputy-lndo',
                'section' => [10,12,15,42,32,39],
                'designation' => 1
            ],
            [
                'name' => 'section Officer Arjun',
                'email' => 'sectionofficerarjun@yopmail.com',
                'mobile_no' => '9980067534',
                'role' => 'section-officer',
                'section' => [39],
                'designation' => 4
            ],
            [
                'name' => 'Rajeev Kumar Das',
                'email' => 'rajeev@yopmail.com',
                'mobile_no' => '9966350900',
                'role' => 'deputy-lndo',
                'section' => [30,38,40,44,26],
                'designation' => 1
            ],
            [
                'name' => 'Ankur Kumar Lal',
                'email' => 'ankur@yopmail.com',
                'mobile_no' => '9963333333',
                'role' => 'section-officer',
                'section' => [38],
                'designation' => 4
            ],
            [
                'name' => 'Pankaj Kumar Jha',
                'email' => 'pankaj@yopmail.com',
                'mobile_no' => '9336352266',
                'role' => 'section-officer',
                'section' => [40],
                'designation' => 4
            ]
       ];

       foreach($users as $user){
            //create user
            $createdUser = User::create([
                'user_type' => 'official',
                'name' => $user['name'],
                'email' => $user['email'],
                'mobile_no' => $user['mobile_no'],
                'password' => Hash::make('12345678'),
                'status' => 1,
                'created_at' => Carbon::now(),
            ]);

            $createdUser->syncRoles($user['role']);

            //linking user with section
            foreach($user['section'] as $section){
                DB::table('section_user')->insert([
                    'user_id' => $createdUser->id,
                    'section_id' => $section,
                    'designation_id' => $user['designation'],
                    'created_at' => Carbon::now(),
                ]);
            }
        }
    }
}

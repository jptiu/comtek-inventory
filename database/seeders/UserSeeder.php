<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = collect([
            [
                'name' => 'Admin',
                'email' => 'admin@comtek.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'created_at' => now(),
                'uuid' => Str::uuid(),
                'photo' => 'admin.jpg',
                'store_name' => 'COMTEK Computer Parts and Accessories Trading',
                'store_address' => 'Unit X 2/F, Iponan Commercial Building, Iponan Cagayan de Oro City',
                'store_email' => 'comtek23@gmail.com'
            ],
            [
                'name' => 'Sales',
                'email' => 'sales@comtek.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'created_at' => now(),
                'uuid' => Str::uuid(),
                'photo' => 'admin.jpg'
            ],
            [
                'name' => 'Sales 2',
                'email' => 'sales2@comtek.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'created_at' => now(),
                'uuid' => Str::uuid(),
                'photo' => 'admin.jpg'
            ]
        ]);

        $users->each(function ($user) {
            User::insert($user);
        });
    }
}

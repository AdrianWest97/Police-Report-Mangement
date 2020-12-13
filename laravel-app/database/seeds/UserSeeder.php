<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        //admin user
        User::create([
            'name'=>"administrator",
            'email'=>"admin@prms.com",
            'is_admin'=>true,
            'password'=>Hash::make("prmsadmin"),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),

        ]);

        //guest user
            User::create([
            'name'=>"guest",
            'email'=>"guest@prms.com",
            'password'=>Hash::make("prmsguest"),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'is_active' => false
        ]);

        //    User::create([
        //      'name'=>"Romaine Brown",
        //      'trn'=>244483455,
        //     'email'=>"brown@gmail.com",
        //     'password'=>"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi",
        // ]);
        //    User::create([
        //      'name'=>"Aurbey walker",
        //      'trn'=>225468456,
        //     'email'=>"simone@.com",
        //     'password'=>"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi",
        // ]);
        //            User::create([
        //      'name'=>"Creg Simpson",
        //      'trn'=>225423456,
        //     'email'=>"sanique@gmail.com",
        //     'password'=>"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi",
        // ]);
        //            User::create([
        //      'name'=>"Jovian Bker",
        //      'trn'=>225433456,
        //     'email'=>"jovian@gmail.com",
        //     'password'=>"$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi",
        // ]);

    }
}

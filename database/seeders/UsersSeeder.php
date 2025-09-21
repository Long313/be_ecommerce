<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User;
        $user->id = '18e00d22-0eb6-4005-9589-5ddcae1986b3';
        $user->fullname = 'Admin';
        $user->email = 'admin@gmail.com';
        $user->phone_number = '0123456789';
        $user->password = Hash::make('1234@Abcd');
        $user->role = 'admin';
        $user->status = 'active';
        $user->gender = 'unisex';
        $user->birthday = now();
        $user->address = '123 đường 456';
        $user->avatar_url = '';
        $user->refresh_token = '';
        $user->created_at = now();
        $user->created_by = '18e00d22-0eb6-4005-9589-5ddcae1986b3';
        $user->updated_at = now();
        $user->updated_by = '18e00d22-0eb6-4005-9589-5ddcae1986b3';
        $user->deleted_at = null;
        $user->deleted_by = null;
        $user->save();
    }
}

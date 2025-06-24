<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = new User;
        // $user->id = Str::uuid()->toString();
        $user->id = '18e00d22-0eb6-4005-9589-5ddcae1986b3'; // ID người dùng, có thể thay đổi nếu cần
        $user->fullname = 'Admin';
        $user->email = 'admin@gmail.com';
        $user->phone_number = '0123456789';
        $user->username = 'admin';
        $user->password = bcrypt('12345678'); // Mã hóa mật khẩu
        $user->role = 'admin';
        $user->refresh_token = Str::random(60); // Tạo refresh token ngẫu nhiên
        $user->created_at = now();
        $user->created_by = '18e00d22-0eb6-4005-9589-5ddcae1986b3'; // Giả sử người tạo là chính mình
        $user->updated_at = now();
        $user->updated_by = '18e00d22-0eb6-4005-9589-5ddcae1986b3'; // Giả sử người cập nhật là chính mình
        $user->deleted_at = null; // Chưa xóa
        $user->deleted_by = null; // Chưa xóa
        $user->save();
    }
}

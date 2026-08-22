<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class AdminUserSeeder extends Seeder
{
public function run(): void
{
// Sử dụng updateOrCreate để tránh lỗi trùng lặp khi chạy lệnh seed nhiều lần
User::updateOrCreate(
['email' => 'admin@example.com'],
[
'name' => 'Admin User',
'password' => Hash::make('password'),
'role' => 'admin',
'email_verified_at' => now(), // Xác thực sẵn email cho tài khoản admin để tránh lỗi phân quyền
]
);
}
}
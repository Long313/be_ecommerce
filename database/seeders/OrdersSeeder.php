<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Order;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $order = new Order;
        // $order->id = Str::uuid()->toString();
        $order->id = '2d7ff9f5-29b7-43d2-955c-6dce3e4caaf2'; // ID đơn hàng, có thể thay đổi nếu cần
        $order->user_id = '18e00d22-0eb6-4005-9589-5ddcae1986b3'; // ID người dùng
        $order->amount = 100.00; // Số tiền đơn hàng
        $order->tax_amount = 10.00; // Số tiền thuế
        $order->total_amount = 110.00; // Tổng số tiền đơn hàng
        $order->status = 'pending'; // Trạng thái đơn hàng
        $order->recipient = 'John Doe'; // Tên người nhận
        $order->address = '123 Main St, City, Country'; // Địa chỉ giao hàng
        $order->phone_number = '0123456789'; // Số điện thoại người nhận
        $order->payment_method = 'cod'; // Phương thức thanh
        $order->payment_status = 'pending'; // Trạng thái thanh toán
        $order->created_by = '18e00d22-0eb6-4005-9589-5ddcae1986b3'; // ID người tạo đơn hàng
        $order->updated_by = '18e00d22-0eb6-4005-9589-5ddcae1986b3'; // ID người cập nhật đơn hàng
        $order->deleted_by = null; // Chưa xóa
        $order->created_at = now(); // Thời gian tạo đơn hàng
        $order->updated_at = now(); // Thời gian cập nhật đơn hàng
        $order->deleted_at = null; // Chưa xóa
        $order->save();
    }
}

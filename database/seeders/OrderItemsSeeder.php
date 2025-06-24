<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\OrderItem;

class OrderItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orderItem = new OrderItem;
        $orderItem->id = Str::uuid()->toString();
        $orderItem->quantity = 2; // Số lượng sản phẩm trong đơn hàng
        $orderItem->unit_price = 50.00; // Giá sản phẩm tại thời điểm
        $orderItem->discount_amount = 0; // Tỷ lệ giảm giá 0%
        $orderItem->tax_amount = 10; // Tỷ lệ thuế 10%
        $orderItem->total_amount = 110.00; // Tổng giá trị sản phẩm
        $orderItem->order_id = '2d7ff9f5-29b7-43d2-955c-6dce3e4caaf2'; // ID đơn hàng, có thể thay đổi nếu cần
        $orderItem->product_id = '74789b18-7717-4617-aa2d-42fa113857ce'; // ID sản phẩm, có thể thay đổi nếu cần
        $orderItem->created_at = now(); // Thời gian tạo
        $orderItem->created_by = '18e00d22-0eb6-4005-9589-5ddcae1986b3'; // ID người tạo
        $orderItem->updated_at = now(); // Thời gian cập nhật
        $orderItem->updated_by = '18e00d22-0eb6-4005-9589-5ddcae1986b3'; // ID người cập nhật
        $orderItem->deleted_at = null; // Chưa xóa
        $orderItem->deleted_by = null; // Chưa xóa
        $orderItem->save();
    }
}

<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Product;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = new Product;
        // $product->id = Str::uuid()->toString();
        $product->id = '74789b18-7717-4617-aa2d-42fa113857ce'; // ID sản phẩm, có thể thay đổi nếu cần
        $product->name = 'Product 1';
        $product->description = 'Description for Product 1';
        $product->price = 50.00; // Giá sản phẩm
        $product->category = 'shoes'; // Danh mục sản phẩm
        $product->gender = 'unisex'; // Giới tính sản phẩm
        $product->discount_rate = 0; // Tỷ lệ giảm giá 0%
        $product->tax_rate = 10; // Tỷ lệ thuế 10%
        $product->inventory_count = 50; // Số lượng tồn kho
        $product->image_url = 'https://example.com/image1.jpg'; // URL hình ảnh sản phẩm
        $product->created_at = now(); // Thời gian tạo
        $product->created_by = '18e00d22-0eb6-4005-9589-5ddcae1986b3';
        $product->is_active = true; // Sản phẩm đang hoạt động
        $product->updated_at = now(); // Thời gian cập nhật
        $product->deleted_at = null; // Chưa xóa
        $product->deleted_by = null; // Chưa xóa
        $product->save();
    }
}

<?php

namespace Database\Seeders;

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
        $orderItem->quantity = 2;
        $orderItem->unit_price = 50.00;
        $orderItem->discount_amount = 0;
        $orderItem->tax_amount = 10;
        $orderItem->total_amount = 110.00;
        $orderItem->order_id = '2d7ff9f5-29b7-43d2-955c-6dce3e4caaf2'; 
        $orderItem->product_id = '74789b18-7717-4617-aa2d-42fa113857ce'; 
        $orderItem->created_at = now();
        $orderItem->created_by = '18e00d22-0eb6-4005-9589-5ddcae1986b3'; 
        $orderItem->updated_at = now();
        $orderItem->updated_by = '18e00d22-0eb6-4005-9589-5ddcae1986b3'; 
        $orderItem->deleted_at = null; 
        $orderItem->deleted_by = null; 
        $orderItem->save();
    }
}

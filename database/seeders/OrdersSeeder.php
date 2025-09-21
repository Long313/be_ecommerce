<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;

class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $order = new Order;
        $order->id = '2d7ff9f5-29b7-43d2-955c-6dce3e4caaf2';
        $order->user_id = '18e00d22-0eb6-4005-9589-5ddcae1986b3';
        $order->amount = 100.00;
        $order->tax_amount = 10.00;
        $order->total_amount = 110.00;
        $order->status = 'pending';
        $order->recipient = 'John Doe';
        $order->address = '123 Main St, City, Country';
        $order->phone_number = '0123456789';
        $order->payment_method = 'cod';
        $order->payment_status = 'pending';
        $order->created_by = '18e00d22-0eb6-4005-9589-5ddcae1986b3';
        $order->updated_by = '18e00d22-0eb6-4005-9589-5ddcae1986b3';
        $order->deleted_by = null; 
        $order->created_at = now(); 
        $order->updated_at = now(); 
        $order->deleted_at = null; 
        $order->save();
    }
}

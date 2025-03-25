<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void {
        $users = User::all();
        $products = Product::all();

        foreach ($users as $user) {
            Order::create([
                'user_id' => $user->id,
                'product_id' => $products->random()->id,
                'quantity' => rand(1, 5),
                'total_price' => rand(10, 500),
                'status' => 'pending',
            ]);
        }
    }
}

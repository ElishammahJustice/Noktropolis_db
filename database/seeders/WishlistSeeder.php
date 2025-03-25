<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    public function run(): void {
        $users = User::all();
        $products = Product::all();

        foreach ($users as $user) {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $products->random()->id,
            ]);
        }
    }
}

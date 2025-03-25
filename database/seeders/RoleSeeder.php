<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['name' => 'Admin', 'slug' => 'admin', 'description' => 'Administrator'],
            ['name' => 'Vendor', 'slug' => 'vendor', 'description' => 'Vendor'],
            ['name' => 'Customer', 'slug' => 'customer', 'description' =>  'customer'],
        ]);
    }
}

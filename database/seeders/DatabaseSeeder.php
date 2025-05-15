<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run AbilitySeeder first
        $this->call([
            AbilitySeeder::class,
            RoleSeeder::class,
            FirstAdminSeeder::class,
            VendorSeeder::class,
        ]);

        // Fetch roles
        $adminRole = Role::where('slug', 'admin')->first();
        $vendorRole = Role::where('slug', 'vendor')->first();
        $customerRole = Role::where('slug', 'customer')->first();

        // Create test users with assigned roles
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role_id' => $adminRole->id,
        ]);

        User::factory()->create([
            'name' => 'Vendor User',
            'email' => 'vendor@example.com',
            'role_id' => $vendorRole->id,
        ]);

        User::factory()->create([
            'name' => 'Customer User',
            'email' => 'customer@example.com',
            'role_id' => $customerRole->id,
        ]);
    }
}

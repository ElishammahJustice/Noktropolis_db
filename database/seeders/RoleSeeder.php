<?php
namespace Database\Seeders;

use App\Models\Ability;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Insert roles if they don't exist already
        $adminRole = Role::firstOrCreate([
            'id' => 1,
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'Administrator',
        ]);

        $vendorRole = Role::firstOrCreate([
            'id' => 2,
            'name' => 'Vendor',
            'slug' => 'vendor',
            'description' => 'Vendor',
        ]);

        $customerRole = Role::firstOrCreate([
            'id' => 3,
            'name' => 'Customer',
            'slug' => 'customer',
            'description' => 'Customer',
        ]);

        // Define abilities
        $adminAbilities = [
            ['name' => 'edit-users', 'slug' => 'edit-users'],
            ['name' => 'view-dashboard', 'slug' => 'view-dashboard'],
            ['name' => 'manage-orders', 'slug' => 'manage-orders'],
            ['name' => 'manage-products', 'slug' => 'manage-products'],
            ['name' => 'view-reports', 'slug' => 'view-reports'],
        ];

        $vendorAbilities = [
            ['name' => 'add-products', 'slug' => 'add-products'],
            ['name' => 'manage-products', 'slug' => 'manage-products'],
            ['name' => 'view-orders', 'slug' => 'view-orders'],
            ['name' => 'view-reports', 'slug' => 'view-reports'],
        ];

        $customerAbilities = [
            ['name' => 'view-dashboard', 'slug' => 'view-dashboard'],
            ['name' => 'view-orders', 'slug' => 'view-orders'],
            ['name' => 'manage-profile', 'slug' => 'manage-profile'],
            ['name' => 'view-products', 'slug' => 'view-products'],
        ];

        // Attach abilities to roles
        foreach ($adminAbilities as $abilityData) {
            $ability = Ability::firstOrCreate($abilityData);
            $adminRole->abilities()->attach($ability);
        }

        foreach ($vendorAbilities as $abilityData) {
            $ability = Ability::firstOrCreate($abilityData);
            $vendorRole->abilities()->attach($ability);
        }

        foreach ($customerAbilities as $abilityData) {
            $ability = Ability::firstOrCreate($abilityData);
            $customerRole->abilities()->attach($ability);
        }
    }
}

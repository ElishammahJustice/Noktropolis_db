<?php
// database/seeders/AbilitySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ability;

class AbilitySeeder extends Seeder
{
    public function run(): void
    {
        Ability::insert([
            ['name' => 'access_admin',            'description' => 'Access admin panel'],
            ['name' => 'create_admin',            'description' => 'Create other admins'],
            ['name' => 'view_pending_users',      'description' => 'View users awaiting approval'],
            ['name' => 'approve_user',            'description' => 'Approve a new user'],
            ['name' => 'assign_roles',            'description' => 'Assign roles to users'],
            ['name' => 'view_users',              'description' => 'View all users'],
            ['name' => 'create_user',             'description' => 'Create users'],
            ['name' => 'view_user',               'description' => 'View a single user'],
            ['name' => 'edit_user',               'description' => 'Edit user details'],
            ['name' => 'delete_user',             'description' => 'Delete a user'],
            ['name' => 'suspend_user',            'description' => 'Suspend a user'],
            ['name' => 'manage_roles',            'description' => 'Create, update, delete roles'],
            ['name' => 'view_vendor_orders',      'description' => 'View own vendor orders'],
            ['name' => 'update_order_status',     'description' => 'Change status of own orders'],
            ['name' => 'view_own_orders',         'description' => 'View your own customer orders'],
            ['name' => 'view_orders',             'description' => 'View all orders (admin)'],
            ['name' => 'manage_orders',           'description' => 'Admin: manage orders'],
            ['name' => 'view_earnings',           'description' => 'Vendor: view earnings'],
            ['name' => 'manage_earnings',         'description' => 'Admin: view/manage all earnings'],
            ['name' => 'view_store_settings',     'description' => 'Vendor: view store settings'],
            ['name' => 'edit_store_settings',     'description' => 'Vendor: update store settings'],
            ['name' => 'view_products',           'description' => 'View all products'],
            ['name' => 'manage_products',         'description' => 'Admin: create/update/delete any product'],
            ['name' => 'add_product',             'description' => 'Vendor: add new products'],
            ['name' => 'view_own_products',       'description' => 'Vendor: view own products'],
            ['name' => 'update_own_product',      'description' => 'Vendor: update own products'],
            ['name' => 'delete_own_product',      'description' => 'Vendor: delete own products'],
            ['name' => 'write_reviews',           'description' => 'Customer: write product reviews'],
            ['name' => 'view_reviews',            'description' => 'Vendor/Admin: view reviews'],
            ['name' => 'manage_site_settings',    'description' => 'Admin: update site settings'],
            ['name' => 'checkout',                'description' => 'Customer: proceed to checkout'],
            ['name' => 'manage_wishlist',         'description' => 'Customer: manage wishlist'],
            // … add any other abilities you need
        ]);
    }
}
 
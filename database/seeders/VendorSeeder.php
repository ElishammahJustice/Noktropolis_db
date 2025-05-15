<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendorRole = Role::where('slug', 'vendor')->firstOrFail();

        $vendor = User::firstOrCreate([
            'email' => 'angelica@noktropolis.com',
        ], [
            'name'        => 'Angelica',
            'password'    => Hash::make('Vendor@2025'),
            'role_id'     => $vendorRole->id,
            'is_approved' => true,
        ]);

        $this->command->info('Vendor created: vendor@noktropolis.com / V3ndor@2025');
    }
}

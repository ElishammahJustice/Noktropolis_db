<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{Role, User};
use Illuminate\Support\Facades\Hash;

class FirstAdminSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();

        if (! $adminRole) {
            $this->command->error('Admin role not found. Run RoleSeeder first.');
            return;
        }

        // Avoid duplicate creation
        $existingAdmin = User::where('email', 'admin@example.com')->first();
        if ($existingAdmin) {
            $this->command->warn('Admin already exists. Skipping...');
            return;
        }

        User::create([
            'name'        => 'Mikel Angelo',
            'email'       => 'wjem303@.com',
            'password'    => Hash::make('987654321'), // Change after first login
            'role_id'     => $adminRole->id,
            'is_approved' => true,
        ]);

        $this->command->info('Admin user created: wjem303@.com / 987654321');
    }
}

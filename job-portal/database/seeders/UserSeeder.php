<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@jobportal.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // Create Admin User Details
        UserDetail::create([
            'user_id' => $admin->id,
            'phone' => '+62812-3456-7890',
            'address' => 'Jl. Admin Raya No. 123, Jakarta',
            'birth_date' => '1990-01-15',
            'gender' => 'male',
        ]);

        // Create Regular User
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'user@jobportal.com',
            'role' => 'user',
            'password' => Hash::make('password'),
        ]);

        // Create Regular User Details
        UserDetail::create([
            'user_id' => $user->id,
            'phone' => '+62812-9876-5432',
            'address' => 'Jl. User Street No. 456, Bandung',
            'birth_date' => '1995-05-20',
            'gender' => 'male',
        ]);

        // Create Second User - Jane Smith
        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@jobportal.com',
            'role' => 'user',
            'password' => Hash::make('password'),
        ]);

        // Create Second User Details
        UserDetail::create([
            'user_id' => $user2->id,
            'phone' => '+62813-1111-2222',
            'address' => 'Jl. Sudirman No. 789, Jakarta Selatan',
            'birth_date' => '1992-08-12',
            'gender' => 'female',
        ]);

        // Create Third User - Michael Johnson
        $user3 = User::create([
            'name' => 'Michael Johnson',
            'email' => 'michael@jobportal.com',
            'role' => 'user',
            'password' => Hash::make('password'),
        ]);

        // Create Third User Details
        UserDetail::create([
            'user_id' => $user3->id,
            'phone' => '+62814-3333-4444',
            'address' => 'Jl. Gatot Subroto No. 321, Surabaya',
            'birth_date' => '1988-12-03',
            'gender' => 'male',
        ]);

        $this->command->info('✅ Users created successfully!');
        $this->command->info('📧 Admin: admin@jobportal.com / password');
        $this->command->info('📧 User: user@jobportal.com / password');
        $this->command->info('📧 Jane: jane@jobportal.com / password');
        $this->command->info('📧 Michael: michael@jobportal.com / password');
    }
}

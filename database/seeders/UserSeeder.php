<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Officer;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $schoolYear = '2024-2025';

        // ✅ Create Program Chair Officer
        $pcOfficer = Officer::create([
            'firstname'      => 'Juan',
            'middle_initial' => 'P',
            'lastname'       => 'Dela Cruz',
            'email'          => 'programchair@centralino.com',
            'position'       => 'Program Chair',
            'school_year'    => $schoolYear,
        ]);

        // ✅ Create IT Officer
        $itOfficer = Officer::create([
            'firstname'      => 'Maria',
            'middle_initial' => 'S',
            'lastname'       => 'Santos',
            'email'          => 'it@centralino.com',
            'position'       => 'IT',
            'school_year'    => $schoolYear,
        ]);

        // ✅ Create Program Chair User
        User::create([
            'first_name'     => 'Juan',
            'middle_initial' => 'P',
            'last_name'      => 'Dela Cruz',
            'email'          => 'programchair@centralino.com',
            'position'       => 'Program Chair',
            'status'         => 'active',
            'officer_id'     => $pcOfficer->id,
            'password'       => Hash::make('ProgramChair@123'),
        ]);

        // ✅ Create IT User
        User::create([
            'first_name'     => 'Maria',
            'middle_initial' => 'S',
            'last_name'      => 'Santos',
            'email'          => 'it@centralino.com',
            'position'       => 'IT',
            'status'         => 'active',
            'officer_id'     => $itOfficer->id,
            'password'       => Hash::make('IT@123456'),
        ]);
    }
}
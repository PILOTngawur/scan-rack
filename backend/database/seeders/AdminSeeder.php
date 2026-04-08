<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@sekolah.sch.id'],
            [
                'FullName'  => 'Administrator',
                'NISNUPTK'  => 123456789,
                'email'     => 'admin@sekolah.sch.id',
                'password'  => Hash::make('admin123'),
                'Role'      => 'admin',
            ]
        );

        $this->command->info('Admin user created: admin@sekolah.sch.id / admin123');
    }
}

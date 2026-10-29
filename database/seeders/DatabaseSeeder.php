<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        $users = [
            ['Administrateur', 'admin@stock.test', 'administrateur'],
            ['Gestionnaire', 'gestionnaire@stock.test', 'gestionnaire'],
            ['Magasinier', 'magasinier@stock.test', 'magasinier'],
        ];

        foreach ($users as [$name, $email, $role]) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $user->syncRoles([$role]);
        }
    }
}

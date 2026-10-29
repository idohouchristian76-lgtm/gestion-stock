<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'products.view', 'products.manage',
            'stock.view', 'stock.move',
            'suppliers.manage', 'orders.manage',
            'reports.view', 'users.manage',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        Role::firstOrCreate(['name' => 'administrateur', 'guard_name' => 'web'])
            ->syncPermissions(Permission::all());

        Role::firstOrCreate(['name' => 'gestionnaire', 'guard_name' => 'web'])
            ->syncPermissions([
                'products.view', 'products.manage',
                'stock.view', 'stock.move',
                'suppliers.manage', 'orders.manage',
                'reports.view',
            ]);

        Role::firstOrCreate(['name' => 'magasinier', 'guard_name' => 'web'])
            ->syncPermissions(['products.view', 'stock.view', 'stock.move']);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class AddPermissionsToCajeroSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Nuevos permisos a garantizar que existan
        $newPermissions = [
            'manage_brokers',
            'manage_investors',
            'manage_platforms',
            'manage_finance',
            'manage_internal_transactions',
        ];

        foreach ($newPermissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        // Obtener el rol cajero y asignarle los nuevos permisos (sin quitar los que ya tiene)
        $cajero = Role::findByName('cajero');

        if ($cajero) {
            $cajero->givePermissionTo($newPermissions);
            $this->command->info('✅ Permisos añadidos correctamente al rol cajero.');
        } else {
            $this->command->error('❌ El rol "cajero" no fue encontrado.');
        }
    }
}

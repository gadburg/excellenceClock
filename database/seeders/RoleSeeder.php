<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //roles
        $role_admin = Role::create(['name' => 'admin']);
        $role_responsable = Role::create(['name' => 'responsable']);

        //permisos existentes
        $permission_create_role = Permission::create(['name' => 'create roles']);
        $permission_read_role = Permission::create(['name' => 'read roles']);
        $permission_update_role = Permission::create(['name' => 'update roles']);
        $permission_delete_role = Permission::create(['name' => 'delete roles']);

        $permission_create_turno = Permission::create(['name' => 'create turno']);
        $permission_read_turno = Permission::create(['name' => 'read turno']);
        $permission_update_turno = Permission::create(['name' => 'update turno']);
        $permission_delete_turno = Permission::create(['name' => 'delete turno']);

        $permission_create_notificacion = Permission::create(['name' => 'create notificacion']);
        $permission_read_notificacion = Permission::create(['name' => 'read notificacion']);
        $permission_update_notificacion = Permission::create(['name' => 'update notificacion']);
        $permission_delete_notificacion = Permission::create(['name' => 'delete notificacion']);

        $permission_create_registro = Permission::create(['name' => 'create registro']);
        $permission_read_registro = Permission::create(['name' => 'read registro']);
        $permission_update_registro = Permission::create(['name' => 'update registro']);
        $permission_delete_registro = Permission::create(['name' => 'delete registro']);

        $permission_create_calendario = Permission::create(['name' => 'create calendario']);
        $permission_read_calendario = Permission::create(['name' => 'read calendario']);
        $permission_update_calendario = Permission::create(['name' => 'update calendario']);
        $permission_delete_calendario = Permission::create(['name' => 'delete calendario']);

        $permission_create_fecha = Permission::create(['name' => 'create fecha']);
        $permission_read_fecha = Permission::create(['name' => 'read fecha']);
        $permission_update_fecha = Permission::create(['name' => 'update fecha']);
        $permission_delete_fecha = Permission::create(['name' => 'delete fecha']);

        $permission_create_empresa = Permission::create(['name' => 'create empresa']);
        $permission_read_empresa = Permission::create(['name' => 'read empresa']);
        $permission_update_empresa = Permission::create(['name' => 'update empresa']);
        $permission_delete_empresa = Permission::create(['name' => 'delete empresa']);

        //asignacion de permisos en grupo para cada rol
            //grupo de permisos admin
            $permissions_admin = [
                $permission_create_role,
                $permission_read_role,
                $permission_update_role,
                $permission_delete_role,
                
                $permission_create_turno,
                $permission_read_turno,
                $permission_update_turno,
                $permission_delete_turno,
                
                $permission_create_notificacion,
                $permission_read_notificacion,
                $permission_update_notificacion,
                $permission_delete_notificacion,
                
                $permission_create_registro,
                $permission_read_registro,
                $permission_update_registro,
                $permission_delete_registro,
                
                $permission_create_calendario,
                $permission_read_calendario,
                $permission_update_calendario,
                $permission_delete_calendario,
                
                $permission_create_fecha,
                $permission_read_fecha,
                $permission_update_fecha,
                $permission_delete_fecha,
                
                $permission_create_empresa,
                $permission_read_empresa,
                $permission_update_empresa,
                $permission_delete_empresa,
            ];
        
            // Grupo de permisos responsable
            $permissions_responsable = [
                $permission_create_turno,
                $permission_read_turno,
                $permission_update_turno,
                $permission_delete_turno,
                
                $permission_create_notificacion,
                $permission_read_notificacion,
                $permission_update_notificacion,
                $permission_delete_notificacion,
                
                $permission_create_registro,
                $permission_read_registro,
                $permission_update_registro,
                $permission_delete_registro,
                
                $permission_create_calendario,
                $permission_read_calendario,
                $permission_update_calendario,
                $permission_delete_calendario,
                
                $permission_create_fecha,
                $permission_read_fecha,
                $permission_update_fecha,
                $permission_delete_fecha,
                
                $permission_create_empresa,
                $permission_read_empresa,
                $permission_update_empresa,
                $permission_delete_empresa,
            ];

        //sincronizacion de grupo de permisos con el rol
        $role_admin->syncPermissions($permissions_admin);
        $role_responsable->syncPermissions($permissions_responsable);
    }
}

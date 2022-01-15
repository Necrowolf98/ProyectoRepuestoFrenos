<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Permission;
use App\Models\Person;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ROLE MODEL
        $administration = Role::create(['name' => 'Administrador', 'description' => 'Supervisor del sistema']);
        $client = Role::create(['name' => 'Cliente', 'description' => 'Cliente del sistema']);
        $supplier = Role::create(['name' => 'Proveedor', 'description' => 'Encargado de proveer productos']);
        $person = Role::create(['name' => 'Persona', 'description' => 'Datos comunes de la persona']);

        // ROLE MODEL
        $role1 = Permission::create(['name' => 'roles.index', 'description' => 'Ver listado de rol']);
        $role2 = Permission::create(['name' => 'roles.create', 'description' => 'Crear rol']);
        $role3 = Permission::create(['name' => 'roles.edit', 'description' => 'Editar rol']);
        $role4 = Permission::create(['name' => 'roles.delete', 'description' => 'Eliminar rol']);

        // PERMISSION MODEL
        $permission1 = Permission::create(['name' => 'permissions.index', 'description' => 'Ver listado de permisos']);
        $permission2 = Permission::create(['name' => 'permissions.create', 'description' => 'Crear permiso']);
        $permission3 = Permission::create(['name' => 'permissions.edit', 'description' => 'Editar permiso']);
        $permission4 = Permission::create(['name' => 'permissions.delete', 'description' => 'Eliminar permiso']);


        // CLIENTE MODEL
        $user1 = Permission::create(['name' => 'users.index', 'description' => 'Ver listado de usuario']);
        $user2 = Permission::create(['name' => 'users.show', 'description' => 'Ver usuario']);
        $user3 = Permission::create(['name' => 'users.create', 'description' => 'Crear cliente']);
        $user4 = Permission::create(['name' => 'users.edit', 'description' => 'Editar cliente']);
        $user5 = Permission::create(['name' => 'users.delete', 'description' => 'Eliminar cliente']);
        $user6 = Permission::create(['name' => 'users.permissions', 'description' => 'Dar permiso a usuarios']);

        // REPUESTO MODEL 
        $repuesto1 = Permission::create(['name' => 'repuesto.index', 'description' => 'Ver listado de repuestos']);
        $repuesto2 = Permission::create(['name' => 'repuesto.create', 'description' => 'Crear repuestos']);
        $repuesto3 = Permission::create(['name' => 'repuesto.edit', 'description' => 'Editar repuestos']);
        $repuesto4 = Permission::create(['name' => 'repuesto.delete', 'description' => 'Eliminar repuestos']);

        // DASHBOARD dashboard
        $dashboard1 = Permission::create(['name' => 'dashboard.index', 'description' => 'Ver listado de vehiculos']);
        $dashboard2 = Permission::create(['name' => 'dashboard.create', 'description' => 'Crear vehiculos']);
        $dashboard3 = Permission::create(['name' => 'dashboard.edit', 'description' => 'Editar vehiculos']);
        $dashboard4 = Permission::create(['name' => 'dashboard.delete', 'description' => 'Eliminar vehiculos']);

        // MARCA MODEL 
        $marca1 = Permission::create(['name' => 'marca.index', 'description' => 'Ver listado de marcas de vehiculos']);
        $marca2 = Permission::create(['name' => 'marca.create', 'description' => 'Crear marcas de vehiculos']);
        $marca3 = Permission::create(['name' => 'marca.edit', 'description' => 'Editar marcas de vehiculos']);
        $marca4 = Permission::create(['name' => 'marca.delete', 'description' => 'Eliminar marcas de vehiculos']);

        $administration->syncPermissions([
            $role1,
            $role2,
            $role3,
            $role4,

            $permission1,
            $permission2,
            $permission3,
            $permission4,

            $user1,
            $user2,
            $user3,
            $user4,
            $user5,
            $user6,

            $repuesto1,
            $repuesto2,
            $repuesto3,
            $repuesto4,

            $dashboard1,
            $dashboard2,
            $dashboard3,
            $dashboard4,

            $marca1,
            $marca2,
            $marca3,
            $marca4,
        ]);


        Person::create([
            'name' => 'Anthony Steven',
            'lastname' => 'Chica Mero',
            'fullname' => 'Anthony Chica Mero',
            'type_document' => 'DNI',
            'number_document' => '131549593-5',
            'direction' => 'La floresta',
            'phone' => '096 922 7858',
            'email' => 'anthonychica.98@gmail.com'
        ]);

        $user_admin = User::create([
            'people_id' => '1',
            'username' => 'tanonx',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);

        $user_admin->syncRoles([$administration]);
    }
}

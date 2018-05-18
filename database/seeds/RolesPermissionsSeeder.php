<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;

class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      
      // Reset cached roles and permissions
      app()['cache']->forget('spatie.permission.cache');
      
      $role = Role::create(['guard_name' => 'admin', 'name' => 'superadmin']);
            // Create a superadmin role for the admin users
      $role = Role::create(['guard_name' => 'web', 'name' => 'administrator']);
            // Create a superadmin role for the admin users
      $role = Role::create(['guard_name' => 'web', 'name' => 'authenticated_user']);
            // Create a superadmin role for the admin users
      $role = Role::create(['guard_name' => 'web', 'name' => 'client']);
      
      
      $permission = Permission::create(['name' => 'view cases']);
      $permission = Permission::create(['name' => 'view clients']);
      $permission = Permission::create(['name' => 'view contacts']);
      $permission = Permission::create(['name' => 'view firm']);
      $permission = Permission::create(['name' => 'view invoices']);
      $permission = Permission::create(['name' => 'view calendar']);
      $permission = Permission::create(['name' => 'view messages']);
      $permission = Permission::create(['name' => 'view tasks']);
      $permission = Permission::create(['name' => 'view documents']);
      $permission = Permission::create(['name' => 'view reports']);
      $permission = Permission::create(['name' => 'view settings']);
      $permission = Permission::create(['name' => 'view firms']); 
      $permission = Permission::create(['name' => 'view users']);       
      $permission = Permission::create(['name' => 'view roles']);       
      $permission = Permission::create(['name' => 'view permissions']);        
      
      $role = Role::findByName('administrator');
      $role->givePermissionTo('view cases');
      $role->givePermissionTo('view clients');   
      $role->givePermissionTo('view contacts');
      $role->givePermissionTo('view firm');
      $role->givePermissionTo('view invoices');
      $role->givePermissionTo('view calendar');
      $role->givePermissionTo('view messages');
      $role->givePermissionTo('view tasks');
      $role->givePermissionTo('view documents');
      $role->givePermissionTo('view reports');
      $role->givePermissionTo('view settings');
      
      /*$role = Role::findByName('superadmin');
      $role->givePermissionTo('view cases');
      $role->givePermissionTo('view clients');   
      $role->givePermissionTo('view contacts');
      $role->givePermissionTo('view firm');
      $role->givePermissionTo('view invoices');
      $role->givePermissionTo('view calendar');
      $role->givePermissionTo('view messages');
      $role->givePermissionTo('view tasks');
      $role->givePermissionTo('view documents');
      $role->givePermissionTo('view reports');
      $role->givePermissionTo('view settings');  
      $role->givePermissionTo('view firms'); 
      $role->givePermissionTo('view users');       
      $role->givePermissionTo('view roles');       
      $role->givePermissionTo('view permissions'); */
      
      DB::table('users')->insert([
        'name' => 'Robert Smith',
        'email' => 'codenut33'.'@gmail.com',
        'password' => bcrypt('123456'),
        'verified' => 1,
      ]);
      
      User::find(1)->assignRole('administrator');
      
      DB::table('firm')->insert([
          'name' => 'Admin Firm',
          'address_1' => '1800 Meridian Ave',
          'state' => 'TX',
          'city' => 'Waco',
          'zip' => '76708',
          'phone' => '2544059664',
      ]);
      
      DB::table('settings')->insert([
        'user_id' => 1,
        'theme' => 'flatly',
        'table_color' => 'light',
        'table_size' => 'lg',
        'tz' => 'America\Chicago',
        'firm_id' => 1,
      ]);
      
      DB::table('views')->insert([
        'view_type' => 'contact',
        'view_data' => json_encode(['id', 'first_name', 'last_name', 'phone'], true),
        'u_id' => 1,
      ]);  

      DB::table('views')->insert([
        'view_type' => 'case',
        'view_data' => json_encode(['id', 'name', 'court_name'], true),
        'u_id' => 1,
      ]); 
      DB::table('views')->insert([
        'view_type' => 'client',
        'view_data' => json_encode(['id', 'first_name', 'last_name', 'phone'], true),
        'u_id' => 1,
      ]);        

    }
}

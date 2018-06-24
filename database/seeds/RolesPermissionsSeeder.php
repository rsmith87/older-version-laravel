<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use App\Uuids;
use Webpatser\Uuid\Uuid;

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
      
      
      $role = Role::findByName('authenticated_user');
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
      /*$role->givePermissionTo('view firms'); 
      $role->givePermissionTo('view users');       
      $role->givePermissionTo('view roles');       
      $role->givePermissionTo('view permissions'); */
      
      /*$role = Role::findByName('client');
      $role->givePermissionTo('view cases');
      $role->givePermissionTo('view invoices'); 
      $role->givePermissionTo('view invoices');
      $role->givePermissionTo('view calendar');
      $role->givePermissionTo('view messages');
      $role->givePermissionTo('view tasks');
      $role->givePermissionTo('view documents');*/
      
      //$uuid = Uuid::generate()->string;
      $user = User::create([
        'email' => 'codenut33@gmail.com',
        'password' => bcrypt('123456'),
        'verified' => 1,
      ]);
      
      DB::table('model_has_roles')->insert([
         'model_type' => 'App\User',
          'model_id' => $user->id,
          'role_id' => 2,
      ]);
      
      $user_second = User::create([
        'email' => 'robby@legalkeeper.com',
        'password' => bcrypt('123456'),
        'verified' => 1,
      ]);
      
      DB::table('model_has_roles')->insert([
         'model_type' => 'App\User',
          'model_id' => $user->id,
          'role_id' => 3,
      ]);      
      
      DB::table('firm')->insert([
          'name' => 'Admin Firm',
          'address_1' => '1800 Meridian Ave',
          'state' => 'TX',
          'city' => 'Waco',
          'zip' => '76708',
          'phone' => '2544059664',
      ]);
      
      DB::table('firm')->insert([
          'name' => 'Admin Second Firm',
          'address_1' => '132 W. Johnson St.',
          'state' => 'TX',
          'city' => 'Hewitt',
          'zip' => '76643',
          'phone' => '2544059664',
      ]);      
      
      DB::table('settings')->insert([
        'user_id' => $user->id,
        'theme' => 'flatly',
        'table_color' => 'light',
        'table_size' => 'lg',
        'tz' => 'America\Chicago',
        'firm_id' => 1,
      ]);

      DB::table('settings')->insert([
        'user_id' => $user_second->id,
        'theme' => 'flatly',
        'table_color' => 'light',
        'table_size' => 'lg',
        'tz' => 'America\Chicago',
        'firm_id' => 2,
      ]);
      
      DB::table('views')->insert([
        'view_type' => 'contact',
        'view_data' => json_encode(['contlient_uuid', 'first_name', 'last_name', 'phone'], true),
        'u_id' =>  $user->id,
      ]);  
      
      DB::table('views')->insert([
        'view_type' => 'contact',
        'view_data' => json_encode(['contlient_uuid', 'first_name', 'last_name', 'phone'], true),
        'u_id' =>  $user_second->id,
      ]);       

      DB::table('views')->insert([
        'view_type' => 'case',
        'view_data' => json_encode(['case_uuid', 'name', 'court_name'], true),
        'u_id' =>  $user->id,
      ]);
      
      DB::table('views')->insert([
        'view_type' => 'case',
        'view_data' => json_encode(['case_uuid', 'name', 'court_name'], true),
        'u_id' =>  $user_second->id,
      ]);       
      DB::table('views')->insert([
        'view_type' => 'client',
        'view_data' => json_encode(['contlient_uuid', 'first_name', 'last_name', 'phone'], true),
        'u_id' =>  $user->id,
      ]);
      
      DB::table('views')->insert([
        'view_type' => 'client',
        'view_data' => json_encode(['contlient_uuid', 'first_name', 'last_name', 'phone'], true),
        'u_id' =>  $user_second->id,
      ]);  


    }
}

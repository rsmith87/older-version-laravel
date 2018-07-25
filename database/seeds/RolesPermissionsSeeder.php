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

      //all the roles used in LGK
      $role_types = [
      	'superadmin' => 'admin',
	      'administrator' => 'web',
	      'authenticated_user' => 'web',
	      'firm_manager' => 'web',
	      'firm_member' => 'web',
	      'client' => 'web',
      ];

      //create all the roles used by LGK
      foreach($role_types as $key=>$index){
	      Role::create(['guard_name' => $index, 'name' => $key]);
      }

      //all permissions used in LGK
      $permissions = [
      	'view cases',
	      'view clients',
	      'view contacts',
	      'view firm',
	      'view invoices',
	      'view calendar',
	      'view messages',
	      'view tasks',
	      'view documents',
	      'view reports',
	      'view settings',
	      'view firms',
	      'view users',
	      'view roles',
	      'view permissions',
	      ];

      //get admin role and assign all permissions to it
	    $role = Role::findByName('administrator');
      foreach($permissions as $permission){
        Permission::create(['name' => $permission]);
	      $role->givePermissionTo($permission);
      }

      
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

	    $role_fm = Role::findByName('firm_manager');
	    $role_fm->givePermissionTo('view cases');
	    $role_fm->givePermissionTo('view clients');
	    $role_fm->givePermissionTo('view contacts');
	    $role_fm->givePermissionTo('view firm');
	    $role_fm->givePermissionTo('view invoices');
	    $role_fm->givePermissionTo('view calendar');
	    $role_fm->givePermissionTo('view messages');
	    $role_fm->givePermissionTo('view tasks');
	    $role_fm->givePermissionTo('view documents');
	    $role_fm->givePermissionTo('view reports');
	    $role_fm->givePermissionTo('view settings');

	    $role_fmm = Role::findByName('firm_member');
	    $role_fmm->givePermissionTo('view cases');
	    $role_fmm->givePermissionTo('view clients');
	    $role_fmm->givePermissionTo('view contacts');
	    $role_fmm->givePermissionTo('view invoices');
	    $role_fmm->givePermissionTo('view calendar');
	    $role_fmm->givePermissionTo('view messages');
	    $role_fmm->givePermissionTo('view tasks');
	    $role_fmm->givePermissionTo('view documents');
	    $role_fmm->givePermissionTo('view reports');
	    $role_fmm->givePermissionTo('view settings');

      $role_c = Role::findByName('client');
      $role_c->givePermissionTo('view cases');
      $role_c->givePermissionTo('view invoices');
      $role_c->givePermissionTo('view calendar');
      $role_c->givePermissionTo('view messages');
      $role_c->givePermissionTo('view tasks');
      $role_c->givePermissionTo('view documents');
      
      //$uuid = Uuid::generate()->string;
      $user = User::create([
        'email' => 'codenut33@gmail.com',
        'name' => 'Robby Smith',
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
          'model_id' => $user_second->id,
          'role_id' => 4,
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
          'name' => 'Legalkeeper Firm',
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

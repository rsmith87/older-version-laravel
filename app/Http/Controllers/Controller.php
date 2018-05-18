<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
       public function create_roles_and_permissions()
    {
      //print_r($this->user->getRoleNames());
      // Create a superadmin role for the admin users
      
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
      
      $role = Role::findByName('superadmin');
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
      $role->givePermissionTo('view permissions');       

      //give master admin administrator role
      //User::find(1)->assignRole('administrator');
      return redirect('/register')->with('status', 'Roles and permissions created!');
     
      
    }
    
    public function email_verified()
    {
      return view('vendor.laravel-user-verification.user-verification');
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\View;
use App\Contact;
use App\LawCase;
use App\User;
use App\Settings;
use App\FirmStripe;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use GuzzleHttp\Client;


class SettingController extends Controller
{
	    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
					$this->user = \Auth::user();
					if(!$this->user){
						return redirect('/login');
					}					
					//if(!$this->user->hasPermissionTo('view settings')){
						//return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
					//}
					$this->settings = Settings::where('user_id', $this->user['id'])->first();
            $this->event_types = ['court', 'client meeting', 'blocker', 'lunch', 'meeting', 'research', 'booked'];

            return $next($request);
        });
    }
    
    public function index(Request $request)
    {
      //gets all themes so we have the list to check against to add selected class to select element
      $themes = \DB::table('theme')->get();
   		
      
      $contact_views = View::where(['u_id' => $this->user['id'], 'view_type' => 'contact'])->first();
      $contact_columns = Contact::getModel()->getConnection()->getSchemaBuilder()->getColumnListing(Contact::getModel()->getTable());
      if(!empty($contact_views)){
        $contact_user_columns = json_decode($contact_views->view_data, true);
        $contact_user_views = json_decode($contact_views, true);
        //print_r($contact_user_views);
        $contact_id = $contact_user_views['u_id'];
      }
      else {
        $contact_user_columns = json_encode($contact_columns, true);
        $contact_user_views = [];
        //$contact_id = \DB::table('views')->max('id') + 1;
      }
      
      $client_views = View::where(['u_id' => $this->user['id'], 'view_type' => 'client'])->first();
      $client_columns = Contact::getModel()->getConnection()->getSchemaBuilder()->getColumnListing(Contact::getModel()->getTable());
      if(!empty($client_views)){
        $client_user_columns = json_decode($client_views->view_data, true);
        $client_user_views = json_decode($client_views, true);
        $client_id = $client_user_views['u_id'];
      }
      else {
        $client_user_columns = json_encode($client_columns, true);
        $client_user_views = [];
        //$client_id = \DB::table('views')->max('id') + 1;
      }   

      $case_views = View::where(['u_id' => $this->user['id'], 'view_type' => 'case'])->first();
      $case_columns = LawCase::getModel()->getConnection()->getSchemaBuilder()->getColumnListing(LawCase::getModel()->getTable());
      if(!empty($case_views)){
        $case_user_columns = json_decode($case_views->view_data, true);
        $case_user_views = json_decode($case_views, true);
        $case_id = $case_user_views['u_id'];
                
      }
      else {
        $case_user_columns = json_encode($case_columns, true);
        $case_user_views = [];
        //$case_id = \DB::table('views')->max('id') + 1;
      }  
      
			
			$fs = FirmStripe::where('firm_id', $this->settings->firm_id)->first();
      
      return view('dashboard/settings', [
        'user' => $this->user, 
        'client_columns' => $client_columns,
        'client_user_columns' => $client_user_columns,
        'client_user_views' => $client_user_views,
        'client_id' => $client_id,
        'contact_columns' => $contact_columns,
        'contact_user_columns' => $contact_user_columns,
        'contact_user_views' => $contact_user_views,
        'contact_id' => $contact_id,
        'case_columns' => $case_columns,
        'case_user_columns' => $case_user_columns,
        'case_user_views' => $case_user_views,
        'case_id' => $case_id,
        'themes' => $themes, 
        'theme' => $this->settings->theme,
        'table_color' => $this->settings->table_color,
        'table_color_options' => ['dark', 'light'],
        'table_sizes' => ['sm', 'lg'],
        'table_size' => $this->settings->table_size,
				'firm_id' => $this->settings->firm_id,   
        'fb' => $this->settings->fb,
        'twitter' => $this->settings->twitter,
        'instagram' => $this->settings->instagram,
        'avvo' => $this->settings->avvo,
        'show_task_calendar' => isset($this->settings->task_calendar) ? $this->settings->task_calendar : "",
      ]);
    }
  
    public function update_view($type, Request $request)
    {
      $data = $request->all();
      $values = [];
      //get all view based on type passed in through URL
      $views = View::where(['u_id' => $this->user['id'], 'view_type' => $type])->first();
      if($data['type'] === 'case'){
        if(!array_key_exists('name', $data)){
          return redirect('/dashboard/settings')->withErrors(['Whoops!  I think you want to see the case name!']);
        }   
      }
      
      if($data['type'] === 'contact'){
        if(!array_key_exists('phone', $data) && !array_key_exists('email', $data) && !array_key_exists('address', $data)){
          return redirect('/dashboard/settings')->withErrors(['You have to select at least one way to connect with a contact!']);

        }
      }
      //getting ID if present
       if(!empty($views->id)){
          $id = $views->id;
       }
       else {
       //Incrementing max ID value if not an ID  
         $id = DB::table('views')->max('id') + 1;
       }   
      //ensures ID is always in the mix
      //array_push($values, 'id');
      foreach($data as $key => $index){

          array_push($values, $key);

        
      }
      $values = array_values(array_diff($values, array('_token', 'type', 'case_id', 'created_at', 'firm_id', 'is_client')));



        View::updateOrCreate(    
        [
          'id' => $id,    
        ], 
        [
          'view_type' => $type,   
          'view_data' => json_encode($values),          
          'user_id' => $this->user['id'],
        ]);
  
     

      return redirect('/dashboard/settings')->withStatus(ucfirst($type) . ' view updated successfully!');
    }
  
    public function update_theme(Request $request)
    {
      $data = $request->all();   
   
      $user = Settings::where('user_id', $this->user['id'])->first();;
      $user->theme = $data['theme_selector'];
      $user->save();
      return redirect('/dashboard/settings')->with('status', 'Theme updated successfully!');
    }
  
    public function table_color(Request $request)
    {
      $data = $request->all();   
   
      $user = Settings::where('user_id', $this->user['id'])->first();;
      $user->table_color = $data['table_color'];
      $user->save();
      return redirect('/dashboard/settings')->with('status', 'Table colors updated successfully!');
    } 
  
    public function table_size(Request $request)
    {
      $data = $request->all();   
  
      $user = Settings::where('user_id', $this->user['id'])->first();
      $user->table_size = $data['table_size'];
      $user->save();
      return redirect('/dashboard/settings')->with('status', 'Table size updated successfully!');
    }
  
    public function show_tasks_calendar(Request $request)
    {
      $data = $request->all();
      
      $user = Settings::where('user_id', $this->user['id'])->first();
      if($user->task_calendar){
        $user->task_calendar = 0;
      } else {
        $user->task_calendar = 1;
      }
      $user->save();
      return redirect('/dashboard/settings')->with('status', 'Tasks will now show on the calendar');
      
    }
	
		public function list_users()
		{
			$users = User::all();
			//$firm_users = Settings::where('firm_id', $this->settings->firm_id)->select('user_id')->get();


			return view('dashboard.users', [
				'users' => $users,
				'name' => $this->user['name'],
				'theme' => $this->settings->theme,
        'table_color' => $this->settings->table_color,
        'table_size' => $this->settings->table_size,
				'firm_id' => $this->settings->firm_id,   
			]);
		}
	
	  public function list_roles()
		{
      $roles = [];
			        $roles = Role::all();//Get all roles

        return view('dashboard.roles', [
				'roles' => $roles,
				'name' => $this->user['name'],
				'theme' => $this->settings->theme,
        'table_color' => $this->settings->table_color,
        'table_size' => $this->settings->table_size,
				'firm_id' => $this->settings->firm_id,
	      'threads' => $this->threads,
	        'user' => $this->user,
				]);
		}
	public function create_permission() {
        $roles = Role::all(); //Get all roles

        return view('dashboard.permission-create', [
					'roles'=>$roles,
									'name' => $this->user['name'],
				'theme' => $this->settings->theme,
        'table_color' => $this->settings->table_color,
        'table_size' => $this->settings->table_size,
				'firm_id' => $this->settings->firm_id,
	        'threads' => $this->threads,
	        'user' => $this->user,
				]);
    }
	
	 public function store_permission(Request $request) {
        $this->validate($request, [
            'name'=>'required|max:40',
        ]);

        $name = $request['name'];
        $permission = new Permission();
        $permission->name = $name;

        $roles = $request['roles'];

        $permission->save();

        if (!empty($request['roles'])) { //If one or more role is selected
            foreach ($roles as $role) {
						
                $r = Role::where('id', $role)->get(); //Match input role to db record
					
                $permission = Permission::where('name', $name)->first(); //Match input //permission to db record
							
                //$r->givePermissionTo($permission);
								$this->user->assignRole($r);
            }
        }

        return redirect()->route('permissions.index')
            ->with('status',
             'Permission'. $permission->name.' added!');

    }
	
	public function store_role(Request $request)
	{
    //Validate name and permissions field
        $this->validate($request, [
            'name'=>'required|unique:roles|max:20',
            'permissions' =>'required',
            ]
        );

        $name = $request['name'];
        $role = new Role();
        $role->name = $name;

        $permissions = $request['permissions'];

        $role->save();
				$role = Role::where('name', $name)->get();

				//Looping thru selected permissions
        foreach ($permissions as $permission) {
            $p = Permission::where('id', $permission)->first(); 
         	  //Fetch the newly created role and assign permission
	          $permission->assignRole($role);
        }
				$role->syncPermissions($permissions);

        return redirect()->route('roles.index')
            ->with('flash_message',
             'Role'. $role->name.' added!'); 
    
	}
	
		public function create_role()
		{
        $permissions = Permission::all();//Get all permissions
					
        return view('dashboard.role-create', [
					'permissions'=>$permissions,
					'name' => $this->user['name'],
					'theme' => $this->settings->theme,
					'table_color' => $this->settings->table_color,
					'table_size' => $this->settings->table_size,
					'firm_id' => $this->settings->firm_id,
	        'threads' => $this->threads,
	        'user' => $this->user,
				]); 
		}
	
		public function update_role(Request $request, $id)
		{
  		 $role = Role::findOrFail($id);//Get role with the given id
    		//Validate name and permission fields
        $this->validate($request, [
            'name'=>'required|max:20|unique:roles,name,'.$id,
            'permissions' =>'required',
        ]);

        $input = $request->except(['permissions']);
        $permissions = $request['permissions'];

        $role->fill($input)->save();


        $p_all = Permission::all();//Get all permissions

        foreach ($p_all as $p) {
            $role->revokePermissionTo($p); //Remove all permissions associated with role
        }		
        foreach ($permissions as $permission) {
          
          $p = Permission::where('id', $permission)->first(); //Get corresponding form //permission in db
          //print_r($p);
          $role->givePermissionTo($p);  //Assign permission to role
        }

        return redirect('/dashboard/settings/roles/')
            ->with('status',
             'Role'. $role->name.' updated!');			
		}
	
		public function edit_role($id)
		{
		    $role = Role::findOrFail($id);
        $permissions = Permission::all();

        return view('dashboard.role-edit', [
					'role' => $role,
					'permissions' => $permissions,
					'name' => $this->user['name'],
					'theme' => $this->settings->theme,
					'table_color' => $this->settings->table_color,
					'table_size' => $this->settings->table_size,
					'firm_id' => $this->settings->firm_id,
	        'threads' => $this->threads,
	        'user' => $this->user,
				]);
		}
	
	public function destroy_role($id)
	{
        $role = Role::find($id);
        $role->delete();

        return redirect()->route('roles.index')
            ->with('status',
             'Role deleted!');

	}
	
		public function list_permissions()
		{
      
			 $permissions = Permission::all(); //Get all permissions
      if(empty($permissions)){
        $permissions = [];
      }
			$users = User::get();

       return view('dashboard.permissions',[
				 'permissions' => $permissions,
					'firm_id' => $this->settings->firm_id,
					'table_color' => $this->settings->table_color,
					'table_size' => $this->settings->table_size,
          'theme' => $this->settings->theme,
           'users' => $users,
	       'threads' => $this->threads,
	       'user' => $this->user,
				 ]);
		}
	 /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy_permission($id) {
        $permission = Permission::findOrFail($id);

    //Make it impossible to delete this specific permission 
    if ($permission->name == "Administer roles & permissions") {
            return redirect()->route('permissions.index')
            ->with('flash_message',
             'Cannot delete this Permission!');
        }

        $permission->delete();

        return redirect()->route('permissions.index')
            ->with('flash_message',
             'Permission deleted!');

    }
	  public function destroy_user()
		{
			 //Find a user with a given id and delete
        $user = User::findOrFail($id); 
        $user->delete();

        return redirect()->route('users.index')
            ->with('flash_message',
             'User successfully deleted.');
    
		}
	
	 public function edit_user($id)
	 {
		  $user = User::findOrFail($id); //Get user with specified id
      $roles = Role::get(); //Get all roles

        return view('dashboard.users-edit', [
					'user' => $user, 
					'roles' => $roles,
					'firm_id' => $this->settings->firm_id,
					'table_color' => $this->settings->table_color,
					'table_size' => $this->settings->table_size,
				'theme' => $this->settings->theme,
	  					
					]); //pass user and roles data to view
	 }
	
		public function stripe_account_create(Request $request)
		{
			$s_client_id = env('STRIPE_CLIENT_ID');
			return redirect()->away('https://connect.stripe.com/express/oauth/authorize?redirect_uri=https://'.env('APP_DOMAIN').'/dashboard/stripe/redirect&client_id='.$s_client_id.'&state='.csrf_token());
		}

		public function stripe_return(Request $request)
		{
			
			//make guzzle call to get account_id for the firm
			$code = $_REQUEST['code'];
			//print_r($code);
			$client = new Client(); //GuzzleHttp\Client
			$response = $client->post('https://connect.stripe.com/oauth/token', [
				'form_params' => [
					'client_secret' => env('STRIPE_SECRET'),
					'code' => $code,
					'grant_type' => 'authorization_code',
				]
			]);
			
			if($response){
				$data = json_decode($response->getBody()->getContents(), true);
				$id = $data['stripe_user_id'];
			}
			
			$fs = FirmStripe::updateOrCreate([
				'firm_id' => $this->settings->firm_id,
			],[
				'stripe_account_id' => $id,
				'user_id' => $this->user['id'],
			]);
			return redirect('/dashboard/firm')->with('status', 'Your Stripe account is created and has been connected!');
		}
  


}



<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\View;
use App\Contact;
use App\LawCase;
use App\Http\Requests;
use App\User;
use App\Settings;
use App\FirmStripe;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use GuzzleHttp\Exception\GuzzleException;
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
						if(!$this->user->hasPermissionTo('view settings')){
							return redirect('/dashboard')->withErrors(['You don\'t have permission to access that page.']);
						}
            $this->settings = Settings::where('user_id', $this->user['id'])->first();
            return $next($request);
        });
    }
    
    public function index(Request $request)
    {
      //gets all themes so we have the list to check against to add selected class to select element
      $themes = \DB::table('theme')->get();
   		
      
      $contact_views = View::where(['u_id' => \Auth::id(), 'view_type' => 'contact'])->first();
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
      
      $client_views = View::where(['u_id' => \Auth::id(), 'view_type' => 'client'])->first();
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

      $case_views = View::where(['u_id' => \Auth::id(), 'view_type' => 'case'])->first();
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
        'user_name' => $this->user['name'], 
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
				'fs' => $fs,
      ]);
    }
  
    public function update_view($type, Request $request)
    {
      $data = $request->all();
      $values = [];
      //get all view based on type passed in through URL
      $views = View::where(['u_id' => \Auth::id(), 'view_type' => $type])->first();
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
          'user_id' => $this->user->id,
        ]);
  
     

      return redirect('/dashboard/settings')->withStatus(ucfirst($type) . ' view updated successfully!');
    }
  
    public function update_theme(Request $request)
    {
      $data = $request->all();   
   
      $user = Settings::where('user_id', \Auth::id())->first();;
      $user->theme = $data['theme_selector'];
      $user->save();
      return redirect('/dashboard/settings')->with('status', 'Theme updated successfully!');
    }
  
    public function table_color(Request $request)
    {
      $data = $request->all();   
   
      $user = Settings::where('user_id', \Auth::id())->first();;
      $user->table_color = $data['table_color'];
      $user->save();
      return redirect('/dashboard/settings')->with('status', 'Table colors updated successfully!');
    } 
  
    public function table_size(Request $request)
    {
      $data = $request->all();   
  
      $user = Settings::where('user_id', \Auth::id())->first();;
      $user->table_size = $data['table_size'];
      $user->save();
      return redirect('/dashboard/settings')->with('status', 'Table size updated successfully!');
    }      
	
		public function stripe_account_create(Request $request)
		{
			$s_client_id = env('STRIPE_CLIENT_ID', 'ca_CY5IehksCRDo83Ev3R0FdzbvKW4VEV7n');
			return redirect()->away('https://connect.stripe.com/express/oauth/authorize?redirect_uri=http://legality-codenerd33.codeanyapp.com/dashboard/stripe/redirect&client_id='.$s_client_id.'&state='.csrf_token());
		}
	
		public function stripe_return(Request $request)
		{
			
			//make guzzle call to get account_id for the firm
			$code = $_REQUEST['code'];
			//print_r($code);
			$client = new Client(); //GuzzleHttp\Client
			$response = $client->post('https://connect.stripe.com/oauth/token', [
				'form_params' => [
					'client_secret' => env('STRIPE_SECRET', 'sk_test_oYiig9R9nDcpbDRPwAt8sKMp'),
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
			return redirect('/dashboard/settings')->with('status', 'Your stripe account is created and has been connected!');
		}
  


}



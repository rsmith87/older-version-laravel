<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\InvoiceCreatedNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
  
    use Notifiable, SoftDeletes;
    protected $table = "contact";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id', 
      'contlient_uuid',
      'prefix', 
      'first_name', 
      'last_name', 
      'relationship',
      'company', 
      'company_title', 
      'phone', 
      'email', 
      'address_1', 
      'address_2', 
      'city', 
      'state', 
      'zip', 
      'case_id', 
      'firm_id', 
      'is_client', 
      'has_login',
      'user_id',
      'created_at',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];
  
    public function cases()
    {
      return $this->hasOne('App\Case', 'id', 'case_id');
    }
  
    public function documents()
    {
        return $this->hasMany('App\Document', 'contact_id');
    }
  
    public function documentsclients()
    {
        return $this->hasMany('App\Document', 'client_id');
    }  
  
    public function getTableColumns() {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
  
    public function tasks()
    {
      return $this->hasMany('App\TaskList', 'contact_client_id');
    }
  
    public function notes()
    {
      return $this->hasMany('App\Note', 'id', 'contact_client_id');
    }
    
    public function user_account()
    {
      return $this->hasOne('App\User', 'id', 'has_login');
    }
  
    public function sendTaskDueReminder($client)
    {
      //print_r($client);
      $user = User::where('id', $client->has_login)->first();
      if(empty($user)){
        $user = $client;
      }
      //print_r($user);
      $user->notify(new InvoiceCreatedNotification($user));
    }    
  
}

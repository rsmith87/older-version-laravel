<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Document extends Model
{
  use SoftDeletes;
    protected $table = "document";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'id',
      'document_uuid',
      'name', 
      'description', 
      'location', 
      'path', 
      'mime_type',
      'case_id', 
      'contact_id', 
      'client_id', 
      'firm_id', 
      'user_id',
      'client_share',
      'created_at',
      'updated_at',
    ];
  
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];    
  
    public function wysiwyg()
    {
      return $this->hasOne('App\WysiwygDocument', 'document_id');
    }
  
    public function lawCase()
    {
      return $this->hasOne('App\LawCase', 'case_id');
    }
    
    public function client()
    {
      return $this->hasOne('App\Contact', 'client_id');
    }
  
    public function contact()
    {
      return $this->hasOne('App\Contact', 'contact_id');
    }
  
    public function firm()
    {
      return $this->hasOne('App\Firm', 'firm_id');
    }
  
    public function user()
    {
      return $this->hasOne('App\User', 'user_id');
    }
}

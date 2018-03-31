<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WysiwygDocument extends Model
{
    protected $table = "ck_data";
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'name', 'description', 'data', 'document_id'
    ];
  
  
  
    public function document() {

      return $this->belongsTo('App\Document', 'document_id', 'id');
    }
}

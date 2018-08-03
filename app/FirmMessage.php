<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FirmMessage extends Model
{
    public $fillable = [
    	'firm_id',
	    'firm_message',
    ];

    public function firm()
    {
    	return $this->belongsTo('App\Firm','id', 'firm_id');
    }
}

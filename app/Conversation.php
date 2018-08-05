<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
	protected $fillable = [
		'id',
		'user_one',
		'user_two',
		'status',
		'created_at',
		'updated_at',
	];

	public function userOne()
	{
		return $this->hasOne('App\User', 'id', 'user_one');
	}

	public function userTwo()
	{
		return $this->hasOne('App\User', 'id', 'user_two');
	}

	public function messages()
	{
		return $this->hasMany('App\Message', 'conversation_id', 'id');
	}
}

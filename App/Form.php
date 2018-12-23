<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_forms';

    public $fillable = [
        'uuid',
        'type',
        'name',
        'data',
        'firm_share',
        'case_id',
        'firm_id',
        'user_id',
    ];

    public function completed()
    {
        return $this->hasMany('App\FormCompleted', 'form_uuid', 'uuid');
    }
}
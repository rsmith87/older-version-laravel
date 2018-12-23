<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormCompleted extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_forms_complete';

    public $fillable = [
        'uuid',
        'form_uuid',
        'user_data',
    ];

    public function form()
    {
        return $this->belongsTo('App\Form', 'uuid', 'form_uuid');
    }
}

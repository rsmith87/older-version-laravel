<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'id',
        'lead_uuid',
        'prefix',
        'first_name',
        'last_name',
        'converted',
        'company',
        'company_title',
        'phone',
        'email',
        'address_1',
        'address_2',
        'city',
        'state',
        'zip',
        'firm_id',
        'user_id',
        'created_at',
    ];
}

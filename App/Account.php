<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;

class Account extends Model
{
    use Billable;

    public $fillable = [
        'id',
        'master_user_id' .
        'stripe_id',
        'card_brand',
        'card_last_four',
        'trial_ends_at',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'id', 'id');
    }

    public function master_user()
    {
        return $this->hasOne('App\User', 'id', 'master_user_id');
    }

    public function subscribed()
    {
        return $this
            ->hasMany(Subscription::class, $this->getForeignKey())
            ->orderBy('created_at', 'desc');
    }
}

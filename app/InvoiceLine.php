<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Invoice;

class InvoiceLine extends Model
{
    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}

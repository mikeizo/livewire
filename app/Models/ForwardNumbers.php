<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForwardNumbers extends Model
{
    public function orderNumber()
    {
        return $this->belongsTo('App\Models\OrderNumbers');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderNumbers extends Model
{
    public function forwardNumber() {
        return $this->hasOne('App\Models\ForwardNumbers', 'order_number_id');
    }
}

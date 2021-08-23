<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CancelledAppointment extends Model
{
    public function cancelled_by() //Buscará un campo que tenga el mismo nombre: cancelled_by_id
    {	// belongsTo Cancellation N - 1 User hasMany
    	return $this->belongsTo(User::class);
    }
}

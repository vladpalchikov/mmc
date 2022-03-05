<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;
use MMC\Models\User;

class Blank extends Model
{
    public function user()
    {
    	return $this->hasOne(User::class, 'id', 'user_id');
    }
}

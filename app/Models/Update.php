<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;

class Update extends Model
{
    protected $fillable = [
    	'version',
    	'update'
    ];
}

<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;

class MMC extends Model
{
    protected $table = 'mmc';
    protected $fillable = ['name', 'title', 'city_code', 'address', 'ip'];
}

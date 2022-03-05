<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $fillable = [
    	'name',
    	'code',
    	'comment',
        'price',
    	'provider',
    ];

    public function service()
    {
        return $this->hasOne('MMC\Models\Service', 'tax_id', 'id');
    }
}

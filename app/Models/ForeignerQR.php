<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;

class ForeignerQR extends Model
{
    protected $table = 'foreigner_qrs';
    public $statuses = [
    	'Не оплачен',
    	'Оплачен',
    	'Возвращен'
    ];

    public function tax()
    {
        return $this->hasOne('MMC\Models\Tax', 'id', 'tax_id');
    }

    public function foreigner()
    {
        return $this->hasOne('MMC\Models\Foreigner', 'id', 'foreigner_id');
    }

    public function user()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'operator_id');
    }

    public function getStatus()
    {
    	return $this->statuses[$this->status];
    }

    // Scopes

    public function scopeEmptyinn($query)
    {
        return $query->where('inn', 0);
    }
}

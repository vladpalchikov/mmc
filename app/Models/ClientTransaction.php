<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;

class ClientTransaction extends Model
{
	public function client()
    {
        return $this->hasOne('MMC\Models\Client', 'id', 'client_id');
    }

    public function operator()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'operator_id');
    }

    public function company()
    {
        return $this->hasOne('MMC\Models\Company', 'id', 'company_id');
    }
}

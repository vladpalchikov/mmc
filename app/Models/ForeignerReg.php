<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;

class ForeignerReg extends Model
{
    public function operator()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'operator_id');
    }

    public function host()
    {
        return $this->hasOne('MMC\Models\Client', 'id', 'client_id');
    }

    public function foreigner()
    {
        return $this->hasOne('MMC\Models\Foreigner', 'id', 'foreigner_id');
    }
}

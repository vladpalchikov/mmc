<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;

class ForeignerServiceGroup extends Model
{
    public function service()
    {
        return $this->hasOne('MMC\Models\Service', 'id', 'service_id');
    }

    public function services()
    {
        return $this->hasMany('MMC\Models\ForeignerService', 'group_id', 'id');
    }

    public function operator()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'operator_id');
    }

    public function cashier()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'cashier_id');
    }

    public function client()
    {
        return $this->hasOne('MMC\Models\Client', 'id', 'client_id');
    }

    public static function countCashless()
    {
        return ForeignerServiceGroup::where('payment_method', 1)->where('payment_status', 0)->count();
    }

    public static function countCash()
    {
        return ForeignerServiceGroup::where('payment_method', 0)->where('payment_status', 0)->count();
    }
}

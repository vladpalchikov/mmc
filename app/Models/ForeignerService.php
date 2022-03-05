<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;

class ForeignerService extends Model
{
    public static $statuses = [
        'Не оплачена',
        'Оплачена'
    ];

    public $typeAppeals = [
        'Первичная регистрация',
        'Продление',
        'Трудовой договор'
    ];

    public function foreigner()
    {
        return $this->hasOne('MMC\Models\Foreigner', 'id', 'foreigner_id');
    }

    public function service()
    {
        return $this->hasOne('MMC\Models\Service', 'id', 'service_id');
    }

    public function operator()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'operator_id');
    }

    public function client()
    {
        return $this->hasOne('MMC\Models\Client', 'id', 'client_id');
    }

    public function cashier()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'cashier_id');
    }

    public function qr()
    {
        return $this->hasOne('MMC\Models\ForeignerQR', 'service_id', 'id');
    }

    public function group()
    {
        return $this->hasOne('MMC\Models\ForeignerServiceGroup', 'id', 'group_id');
    }

    public static function countCashless()
    {
        return ForeignerService::where('is_mu', 0)->where('payment_method', 1)->where('payment_status', 0)->count();
    }

    public static function countCash()
    {
        return ForeignerService::where('is_mu', 0)->where('payment_method', 0)->where('payment_status', 0)->count();
    }

    public function getTypeAppeal()
    {
        return $this->typeAppeals[$this->type_appeal];
    }

    // Scopes

    public function scopeByDate($query, $dateFrom, $dateTo) 
    {
        return $query->whereDate('created_at' , '>=', Helper::formatDateForQuery($dateFrom))
                    ->whereDate('created_at' , '<=', Helper::formatDateForQuery($dateTo));
    }

    public function scopeServicesByOperator($query, $operator_id) 
    {
        return $query->where('operator_id', $operator_id);
    }

    public function scopePayedServicesByOperator($query, $operator_id) 
    {
        return $query->where('payment_status', 1)->where('repayment_status', 0)->where('operator_id', $operator_id);
    }

    public function scopeRepaymentsByOperator($query, $operator_id) 
    {
        return $query->where('operator_id', $operator_id)->where('repayment_status', 3);
    }

    public function scopePaymentCash($query, $operator_id) 
    {
        return $query->where('operator_id', $operator_id)->where('payment_status', 1)->where('repayment_status', 0)->where('payment_method', 0);
    }

    public function scopePaymentCashless($query, $operator_id) 
    {
        return $query->where('operator_id', $operator_id)->where('payment_status', 1)->where('repayment_status', 0)->where('payment_method', 1);
    }

    public function scopeTotalPayed($query, $operator_id) 
    {
        return $query->where('operator_id', $operator_id)->where('payment_status', 1)->where('repayment_status', 0);
    }

    
}

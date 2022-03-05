<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;
use MMC\Models\Service;
use MMC\Models\ForeignerService;
use MMC\Models\ClientTransaction;

class Company extends Model
{
    protected $fillable = ['name'];

    public function getBalance($client)
    {
        if ($client) {
            $services = Service::where('company_id', $this->id)->pluck('id')->toArray();
            return $this->getClientTransactionsSum($client) - ForeignerService::whereIn('service_id', $services)->where('client_id', $client->id)->where('payment_status', 1)->where('payment_method', 1)->where('repayment_status', '<>', 3)->sum('service_price');
        } else {
            return 0;
        }
    }

    public function getClientTransactionsSum($client)
    {
        if ($client) {
    	   return ClientTransaction::where('client_id', $client->id)->where('company_id', $this->id)->sum('sum'); 
        } else {
            return 0;
        }
    }

    public function transactions()
    {
        return $this->hasMany('MMC\Models\ClientTransaction', 'company_id', 'id');
    }
}

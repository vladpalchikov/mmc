<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;
use MMC\Models\ForeignerService;
use MMC\Models\ForeignerServiceGroup;
use MMC\Models\ClientTransaction;
use MMC\Models\Foreigner;
use MMC\Models\User;

class Client extends Model
{
    protected $fillable = [
    	'name',
    	'email',
	    'contact_person',
	    'operator_id',
	    'inn',
	    'type',
		'organization_form',
		'organization_fullname',
		'organization_inn',
		'organization_address',
		'organization_manager',
		'organization_contact_person',
		'organization_contact_phone',
		'organization_requisite_inn',
		'organization_requisite_account',
		'organization_requisite_bank',
		'organization_requisite_bik',
		'organization_requisite_city',
		'organization_requisite_correspondent',
		'person_fullname',
		'person_birthday',
		'person_document',
		'person_document_series',
		'person_document_number',
		'person_document_date',
		'person_document_issuedby',
		'person_document_address',
        'person_document_phone',
        'address_line2',
        'address_line3',
        'organization_address_line2',
        'organization_address_line3',
		'is_host_only',
    ];

    public function operator()
    {
        return $this->hasOne(User::class, 'id', 'operator_id');
    }

    public function operatorUpdated()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

    public function tmservices()
    {
        return $this->hasMany(ForeignerService::class, 'client_id', 'id')->where('is_mu', 0)->orderBy('created_at', 'desc');
    }

    public function services()
    {
        return $this->hasMany(ForeignerService::class, 'client_id', 'id')->orderBy('created_at', 'desc');
    }

    public function groups()
    {
        return $this->hasMany(ForeignerServiceGroup::class, 'client_id', 'id')->orderBy('created_at', 'desc');
    }

    public function transactions()
    {
        return $this->hasMany(ClientTransaction::class, 'client_id', 'id')->orderBy('created_at', 'desc');
    }

    public function foreigners()
    {
        return $this->hasMany(Foreigner::class, 'host_id', 'id')->orderBy('created_at', 'desc');
    }

    public function getBalance()
    {
    	return $this->transactions()->sum('sum') - $this->services()->where('payment_status', 1)->where('payment_method', 1)->where('repayment_status', '<>', 3)->sum('service_price');
    }

    public function getDebts()
    {
    	return $this->services()->where('payment_status', 0)->where('payment_method', 1)->where('repayment_status', '<>', 3)->sum('service_price');
    }

    public function getPaid()
    {
    	return $this->services()->where('payment_status', 1)->where('payment_method', 1)->where('repayment_status', '<>', 3)->sum('service_price');
    }
}

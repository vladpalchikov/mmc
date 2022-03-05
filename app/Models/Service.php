<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;
use MMC\Models\ServiceComplex;

class Service extends Model
{
    protected $fillable = [
    	'name',
		'description',
		'service_included',
		'price',
		'order',
		'status',
		'company_id',
		'tax_id',
		'module',
        'agent_compensation',
        'principal_sum',
        'is_complex',
        'labor_exchange',
    ];

    public static $modules = [
    	'Трудовая миграция',
    	'Миграционный учет',
        'Блок гражданство'
    ];

    public function tax()
    {
        return $this->hasOne('MMC\Models\Tax', 'id', 'tax_id');
    }

    public function company()
    {
        return $this->hasOne('MMC\Models\Company', 'id', 'company_id');
    }

    public function getCompanyComplex($company_id)
    {
        if (ServiceComplex::where('service_id', $this->id)->where('company_id', $company_id)->count() > 0) {
            if (ServiceComplex::where('service_id', $this->id)->where('company_id', $company_id)->first()->price == 0) {
                return null;
            } else {
                return ServiceComplex::where('service_id', $this->id)->where('company_id', $company_id)->first()->price;
            }
        } else {
            return null;
        }
    }
}

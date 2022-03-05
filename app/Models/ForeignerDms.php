<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;

class ForeignerDms extends Model
{
    protected $fillable = [
    	'dms_surname',
		'dms_name',
		'dms_middle_name',
		'dms_birthday',
		'dms_nationality',
		'dms_gender',
		'dms_address',
		'dms_address_line2',
		'dms_address_line3',
		'dms_registration_date',
		'dms_document',
		'dms_document_series',
		'dms_document_number',
		'dms_document_date',
		'dms_document_issuedby',
		'dms_registration_ip_date',
		'dms_registration_document',
		'dms_payment',
		'dms_receipt',
		'dms_contract_date',
		'dms_policy_date_from',
		'dms_policy_date_to'
    ];

    public function operator()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'operator_id');
    }

    public function updatedby()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'updated_by');
    }
}

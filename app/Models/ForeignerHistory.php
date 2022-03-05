<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;

class ForeignerHistory extends Model
{
	protected $fillable = [
	    'foreigner_id',
	    'operator_id',
	    'history_created_at',
	    'surname',
	    'name',
	    'middle_name',
	    'birthday',
	    'nationality',
	    'nationality_line2',
	    'gender',
	    'document_name',
	    'document_series',
	    'document_number',
	    'document_date',
	    'document_issuedby',
	    'phone',
	    'inn',
	    'inn_date',
	    'address',
	    'address_line2',
	    'address_line3',
	    'registration_date',
	    'oktmo',
	];

	public function operator()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'operator_id');
    }
}

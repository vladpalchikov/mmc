<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;

class ForeignerPatentChange extends Model
{
    protected $fillable = [
    	'application_id',
		'surname_change',
		'name_change',
		'middle_name_change',
		'document_name_change',
		'document_series_change',
		'document_number_change',
		'document_issued_change',
		'document_date_change',
		'patent_series_change',
		'patent_number_change',
		'blank_patent_series_change',
		'blank_patent_number_change',
		'profession_change',
		'profession_line2_change',
		'date_change',
		'reason',
    ];

    public function operator()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'operator_id');
    }

    public function updatedby()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'updated_by');
    }

    public function foreigner()
    {
        return $this->hasOne('MMC\Models\Foreigner', 'id', 'foreigner_id');
    }
}

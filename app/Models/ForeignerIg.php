<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;

class ForeignerIg extends Model
{
    protected $fillable = [
		'place_birthday_country',
		'place_birthday_city',
		'residence_type',
		'residence_series',
		'residence_number',
		'residence_date_from',
		'residence_date_to',
		'entry_purpose',
		'profession',
		'qualification',
		'enter_date_from',
		'enter_date_to',
		'migration_card_series',
		'migration_card_number',
		'area',
		'region',
		'city',
		'street',
		'house',
		'housing',
		'building',
		'flat',
		'place_area',
		'place_region',
		'place_city',
		'place_street',
		'place_house',
		'place_housing',
		'place_building',
		'place_flat',
		'place_phone',
		'representatives',
		'representatives_line2',
		'representatives_line3',
		'representatives_line4',
		'representatives_line5',
		'prev_address_line1',
		'prev_address_line2',
		'prev_address_line3',
		'receiving_surname',
		'receiving_name',
		'receiving_middle_name',
		'receiving_birthday',
		'receiving_document_name',
		'receiving_document_series',
		'receiving_document_number',
		'receiving_document_date_from',
		'receiving_document_date_to',
		'receiving_area',
		'receiving_region',
		'receiving_city',
		'receiving_street',
		'receiving_house',
		'receiving_housing',
		'receiving_building',
		'receiving_flat',
		'receiving_phone',
		'receiving_org_name',
		'receiving_org_name_line2',
		'receiving_org_address',
		'receiving_org_address_line2',
		'receiving_org_inn',
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

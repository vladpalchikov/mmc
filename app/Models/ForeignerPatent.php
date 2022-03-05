<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;
use MMC\Models\ForeignerService;

class ForeignerPatent extends Model
{
    protected $fillable = [
		'name_change',
		'birthday_place',
		'migration_card_number',
		'migration_card_date',
		'registration_address',
		'registration_address_line2',
		'registration_date_from',
		'registration_date_to',
		'document_organization',
		'inn',
		'inn_date',
		'russian_document',
		'russian_document_line2',
		'russian_series',
		'russian_number',
		'russian_date',
		'work_activity',
		'profession',
		'profession_line2',
		'work_until',
		'prev_patent',
		'prev_patent_line2',
		'prev_patent_series',
		'prev_patent_number',
		'prev_patent_blank_series',
		'prev_patent_blank_number',
		'prev_patent_date_from',
		'prev_patent_date_to',
		'application_from',
		'document_date_incoming',
        'surname',
        'name',
        'birthday',
        'nationality',
        'gender',
        'nationality',
        'nationality_line2',
        'document_name',
        'document_series',
        'document_number',
        'document_date',
        'address',
        'address_line2',
        'address_line3',
        'inn',
        'phone',
    ];

    public function operator()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'operator_id');
    }

    public function docuser()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'uo_user');
    }

    public function foreigner()
    {
        return $this->hasOne('MMC\Models\Foreigner', 'id', 'foreigner_id');
    }

    public function updatedby()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'updated_by');
    }

    public function serviceLabor()
    {
        return ForeignerService::where('foreigner_id', $this->foreigner->id)->where('service_id', 52)->where('payment_status', 1)->first();
    }

    public function scopeByDate($query, $dateFrom, $dateTo)
    {
        return $query->whereDate('created_at' , '>=', Helper::formatDateForQuery($dateFrom))
                    ->whereDate('created_at' , '<=', Helper::formatDateForQuery($dateTo));
    }
}

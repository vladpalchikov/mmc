<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Auth;

class Foreigner extends Model
{
    protected $fillable = [
        'surname',
        'name',
        'middle_name',
        'birthday',
        'nationality',
        'nationality_line2',
        'document_name',
        'document_series',
        'document_number',
        'document_date',
        'document_date_to',
        'document_issuedby',
        'registration_date',
        'gender',
        'address',
        'address_line2',
        'address_line3',
        'phone',
        'oktmo',
        'district',
        'inn',
        'inn_date',
        'ifns_name',
        'created_at',
        'oktmo_fail',
        'inn_check',
        'host_id',
        'is_host_available',
        'name_change',
        'birthday_place',
        'registration_address',
        'registration_address_line2',
        'russian_document',
        'russian_number',
        'russian_series',
        'russian_date',
        'work_activity',
        'prev_patent',
        'prev_patent_series',
        'prev_patent_number',
        'prev_patent_blank_series',
        'prev_patent_blank_number',
        'prev_patent_date_from',
        'prev_patent_date_to',
    ];

    public static $statuses = [
        'Не оплачена',
        'Оплачена',
        'Закрыта'
    ];

    public function services()
    {
        return $this->hasMany('MMC\Models\ForeignerService')->orderBy('service_order', 'asc');
    }

    public function history()
    {
        return $this->hasMany('MMC\Models\ForeignerHistory')->orderBy('created_at', 'desc');
    }

    public function dms()
    {
        return $this->hasMany('MMC\Models\ForeignerDms')->orderBy('updated_at', 'desc');
    }

    public function blanks()
    {
        return $this->hasMany('MMC\Models\ForeignerBlank')->orderBy('updated_at', 'desc');
    }

    public function ig()
    {
        return $this->hasMany('MMC\Models\ForeignerIg')->orderBy('updated_at', 'desc');
    }

    public function patent()
    {
        return $this->hasMany('MMC\Models\ForeignerPatent')->orderBy('updated_at', 'desc');
    }

    public function patentchange()
    {
        return $this->hasMany('MMC\Models\ForeignerPatentChange')->orderBy('updated_at', 'desc');
    }

    public function patentrecertifying()
    {
        return $this->hasMany('MMC\Models\ForeignerPatentRecertifying')->orderBy('updated_at', 'desc');
    }

    public function qr()
    {
        return $this->hasMany('MMC\Models\ForeignerQR')->orderBy('updated_at', 'desc');
    }

    public function servicesByCreated()
    {
        return $this->hasMany('MMC\Models\ForeignerService')->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'operator_id');
    }

    public function operator()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'operator_id');
    }

    public function updatedByUser()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'updated_by');
    }

    public function todayServices()
    {
        return $this->hasMany('MMC\Models\ForeignerService')->whereRaw('Date(created_at) = CURDATE()')->orderBy('service_order', 'asc');
    }

    public function host()
    {
        return $this->hasOne('MMC\Models\Client', 'id', 'host_id');
    }

    public function scopeByDate($query, $dateFrom, $dateTo)
    {
        return $query->whereDate('created_at' , '>=', Helper::formatDateForQuery($dateFrom))
                    ->whereDate('created_at' , '<=', Helper::formatDateForQuery($dateTo));
    }

    public function documentIsset()
    {
        if ($this->dms->count() > 0) return true;
        if ($this->ig->count() > 0) return true;
        if ($this->patent->count() > 0) return true;
        if ($this->patentchange->count() > 0) return true;
        if ($this->patentrecertifying->count() > 0) return true;

        return false;
    }

    public function servicesByDates()
    {
        $request = new Request;

        $services = new \MMC\Models\ForeignerService;

        if ($request->has('date_from')) {
            $services = $services->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->has('date_to')) {
            $services = $services->whereDate('created_at', '<=', $request->get('date_to'));
        }

        if (!$request->has('date_from') && !$request->has('date_to')) {
            $services = $services->whereRaw('Date(created_at) = CURDATE()');
        }

        return $services->where('application_id', '=', $this->id)->orderBy('service_order', 'asc')->get();
    }

    public static function totalServicesToday()
    {
        $todayApplications = \MMC\Models\Foreigner::where('operator_id', '=', Auth::user()->id)->whereRaw('Date(created_at) = CURDATE()')->get();

        $totalServices = 0;
        foreach ($todayApplications as $foreigner) {
            foreach ($foreigner->services as $service) {
                $totalServices++;
            }
        }

        return $totalServices;
    }

    public static function totalServicesTodayByApplication($all = false)
    {
        $totalClients = 0;

        if ($all) {
            $services = new \MMC\Models\ForeignerService;
            $request = new Request;

            if ($request->has('date_from')) {
                $services = $services->whereDate('created_at', '>=', $request->get('date_from'));
            }

            if ($request->has('date_to')) {
                $services = $services->whereDate('created_at', '<=', $request->get('date_to'));
            }

            if (!$request->has('date_from') && !$request->has('date_to')) {
                $services = $services->whereRaw('Date(created_at) = CURDATE()');
            }

            $totalClients = count($services->groupBy('application_id')->get());
        } else {
            $foreigners = \MMC\Models\Foreigner::where('operator_id', '=', Auth::user()->id)->get();

            foreach ($foreigners as $foreigner) {
                foreach ($foreigner->todayServices->groupBy('application_id') as $service) {
                    $totalClients++;
                }
            }
        }

        return $totalClients;
    }
}

<?php

namespace MMC\Http\Controllers\Operator\Report;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;
use Helper;
use MMC\Models\ForeignerService;
use MMC\Models\ForeignerServiceGroup;
use MMC\Models\ForeignerDms;
use MMC\Models\ForeignerIg;
use MMC\Models\ForeignerPatent;
use MMC\Models\ForeignerPatentChange;
use MMC\Models\ForeignerPatentRecertifying;
use MMC\Models\Client;
use MMC\Models\User;
use Storage;

class MUAnalyticsReportController extends Controller
{
    public $reportServices = [];
    public $reportData = [];
    public $clientsData = [];
    public $nationalityData = [];
    public $is_nationality = false;

    public function __construct(Request $request)
    {
        $this->daterange[0] = date('d.m.y');
        $this->daterange[1] = date('d.m.y');

        $reportServices = ForeignerService::where('is_mu', true);

        if ($request->has('daterange')) {
            $this->daterange = explode('-', $request->get('daterange'));
            if (count($this->daterange) == 1) {
                $reportServices = $reportServices->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
            } else {
                $reportServices = $reportServices->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
                $reportServices = $reportServices->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
            }
        } else {
            $reportServices = $reportServices->whereDate('created_at', '=', \Carbon\Carbon::today()->toDateString());
        }

        $reportServices = $reportServices->get();

        if (!$request->has('nationality')) {
            $reportData['count_services'] = $reportServices->count();
            $reportData['count_clients'] = $reportServices->unique('client_id')->count();
            $reportData['count_foreigners'] = $reportServices->unique('foreigner_id')->count();
            $reportData['count_type_appeal_0'] = $reportServices->where('type_appeal', 0)->count();
            $reportData['count_type_appeal_1'] = $reportServices->where('type_appeal', 1)->count();
            $reportData['count_type_appeal_2'] = $reportServices->where('type_appeal', 2)->count();

            $clientsData = [];
            foreach ($reportServices->unique('client_id') as $service) {
                if (!isset($clientsData[$service->client_id])) {
                    if ($service->client) {
                        $clientsData[$service->client_id]['name'] = $service->client->name;
                        $clientsData[$service->client_id]['count'] = $reportServices->where('client_id', $service->client_id)->count();
                        $clientsData[$service->client_id]['count_type_appeal_0'] = $reportServices->where('client_id', $service->client_id)->where('type_appeal', 0)->count();
                        $clientsData[$service->client_id]['count_type_appeal_1'] = $reportServices->where('client_id', $service->client_id)->where('type_appeal', 1)->count();
                        $clientsData[$service->client_id]['count_type_appeal_2'] = $reportServices->where('client_id', $service->client_id)->where('type_appeal', 2)->count();
                    }
                }
            }

            uasort($clientsData, function($a, $b) {
                return $b['count'] <=> $a['count'];
            });

            $this->reportData = $reportData;
            $this->clientsData = $clientsData;
            $this->is_nationality = false;
        } else {
            $nationalityData = [];
            foreach ($reportServices as $service) {
                if ($service->foreigner) {
                    $nationality = mb_strtolower($service->foreigner->nationality);

                    if (!isset($nationalityData[$nationality])) {
                        $nationalityData[$nationality]['count'] = 0;
                        $nationalityData[$nationality]['count_type_appeal_0'] = 0;
                        $nationalityData[$nationality]['count_type_appeal_1'] = 0;
                        $nationalityData[$nationality]['count_type_appeal_2'] = 0;
                    }

                    $nationalityData[$nationality]['nationality'] = $nationality;
                    $nationalityData[$nationality]['count']++;

                    if ($service->type_appeal == 0) {
                        $nationalityData[$nationality]['count_type_appeal_0']++;
                    }

                    if ($service->type_appeal == 1) {
                        $nationalityData[$nationality]['count_type_appeal_1']++;
                    }

                    if ($service->type_appeal == 2) {
                        $nationalityData[$nationality]['count_type_appeal_2']++;
                    }
                }
            }

            uasort($nationalityData, function($a, $b) {
                return $b['count'] <=> $a['count'];
            });

            $this->nationalityData = $nationalityData;
            $this->is_nationality = true;
        }
    }

    public function index(Request $request)
    {
        if ($request->has('export')) {
            return $this->export();
        }

        return view('report.muanalytics.index', [
            'daterange' => $this->daterange,
            'reportData' => $this->reportData,
            'clientsData' => $this->clientsData,
            'nationalityData' => $this->nationalityData,
            'is_nationality' => $this->is_nationality,
        ]);
    }

    public function print()
    {
        return view('report.muanalytics.print', [
            'daterange' => $this->daterange,
            'export' => false,
            'reportData' => $this->reportData,
            'clientsData' => $this->clientsData,
            'nationalityData' => $this->nationalityData,
            'is_nationality' => $this->is_nationality,
        ]);
    }

    public function export()
    {
        if ($this->currectManager) {
            $manager = str_replace(' ', '_', User::find($this->currectManager)->name);
        } else {
            $manager = 'Все_менеджеры';
        }

        if ($this->currectClient) {
            $client = str_replace(' ', '_', Client::find($this->currectClient)->name);
        } else {
            $client = 'Все_Плательщики';
        }

        $filename = 'МУ_'.$manager.'_'.$client.'_'.$this->daterange[0].'-'.$this->daterange[1];
        $file = \Excel::create($filename, function($excel) {
            $excel->sheet('New sheet', function($sheet) {
                $sheet->loadView('report.muanalytics.table', [
                    'daterange' => $this->daterange,
                    'export' => true,
                    'reportData' => $this->reportData,
                ]);
            });
        })->store('csv');

        $file = Storage::disk('export')->get($filename.'.csv');
        $encoded = mb_convert_encoding($file, 'Windows-1251', 'UTF-8');
        Storage::disk('export_public')->put($filename.'.csv', $encoded);

        return response()->download(public_path().'/exports/'.$filename.'.csv');
    }
}

<?php

namespace MMC\Http\Controllers\Operator\Report;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Auth;
use Helper;
use MMC\Models\Foreigner;
use MMC\Models\ForeignerReg;
use MMC\Models\Service;
use MMC\Models\UserMMC;
use MMC\Models\Client;
use MMC\Models\ForeignerService;
use MMC\Models\ForeignerDms;
use MMC\Models\ForeignerIg;
use MMC\Models\ForeignerPatent;
use MMC\Models\ForeignerPatentChange;
use MMC\Models\ForeignerPatentRecertifying;
use Storage;

class RegistrationReportController extends Controller
{
	public $hosts;
	public $clients;
	public $operators;
	public $daterange;

	public function __construct(Request $request)
	{
		$foreigners = ForeignerReg::orderBy('created_at');
		$this->daterange[0] = date('d.m.y');
		$this->daterange[1] = date('d.m.y');

		if (Auth::user()->hasRole('business.manager|managertmsn|accountant')) {
			$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
			foreach (\MMC\Models\User::where('active', '=', 1)->whereIn('id', $mmcUsers)->orderBy('name', 'asc')->get() as $user) {
				if ($user->hasRole('managertmsn|managertm')) {
					$this->operators[] = $user;
				}
			}
		} else {
			foreach (\MMC\Models\User::where('active', '=', 1)->orderBy('name', 'asc')->get() as $user) {
				if ($user->hasRole('managertmsn|managertm')) {
					$this->operators[] = $user;
				}
			}
		}

		if ($request->has('daterange')) {
			$this->daterange = explode('-', $request->get('daterange'));
			if (count($this->daterange) == 1) {
				$foreigners = $foreigners->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
			} else {
				$foreigners = $foreigners->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
				$foreigners = $foreigners->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
			}
		} else {
			$foreigners = $foreigners->whereRaw('Date(created_at) = CURDATE()');
		}

		$this->clients = Client::whereIn('id', $foreigners->get(['client_id'])->unique('client_id')->toArray())->orderBy('name')->get();
		$this->clientsForFilter = Client::orderBy('name')->get();

		if ($request->has('client')) {
			$this->clients = $this->clients->where('id' , '=', $request->get('client'));
		}

		foreach ($this->clients as $client) {
			if ($request->has('daterange')) {
				$this->daterange = explode('-', $request->get('daterange'));
				if (count($this->daterange) == 1) {
					$client->foreigner_count = ForeignerReg::whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]))
															->where('client_id', $client->id)->count();
				} else {
					$client->foreigner_count = ForeignerReg::whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]))
															->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]))
															->where('client_id', $client->id)->count();
				}
			} else {
				$client->foreigner_count = ForeignerReg::whereRaw('Date(created_at) = CURDATE()')->where('client_id', $client->id)->count();
			}
		}

		$this->clients = $this->clients->sortByDesc(function($client) {
			return $client->foreigner_count;
		});
	}

	public function index(Request $request)
	{
		if ($request->has('export')) {
			return $this->export();
		}

		return view('report.registration.index', [
			'operators' => $this->operators,
			'daterange' => $this->daterange,
			'clients' => $this->clients,
			'clientsForFilter' => $this->clientsForFilter,
		]);
	}

	public function print()
	{
		return view('report.registration.print', [
			'operators' => $this->operators,
			'daterange' => $this->daterange,
			'clients' => $this->clients,
			'clientsForFilter' => $this->clientsForFilter,
		]);
	}

	public function export()
	{
		$filename = 'Принимающие_стороны_'.$this->daterange[0].'-'.$this->daterange[1];
		$file = \Excel::create($filename, function($excel) {
		    $excel->sheet('New sheet', function($sheet) {
		        $sheet->loadView('report.registration.table', [
		        	'clients' => $this->clients,
		        ]);
		    });
		})->store('csv');

		$file = Storage::disk('export')->get($filename.'.csv');
		$encoded = mb_convert_encoding($file, 'Windows-1251', 'UTF-8');
		Storage::disk('export_public')->put($filename.'.csv', $encoded);

		return response()->download(public_path().'/exports/'.$filename.'.csv');
	}
}
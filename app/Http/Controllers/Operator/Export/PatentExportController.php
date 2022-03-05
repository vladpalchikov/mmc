<?php

namespace MMC\Http\Controllers\Operator\Export;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Auth;
use Helper;
use MMC\Models\User;
use MMC\Models\MMC;
use MMC\Models\UserMMC;
use MMC\Models\Foreigner;
use MMC\Models\ForeignerPatent;
use MMC\Models\ForeignerService;
use Storage;

class PatentExportController extends Controller
{
	public $patents;
	public $mmc;
	public $daterange;
	public $currentMMC;

	public function __construct(Request $request)
	{
		$this->mmc = MMC::all();
		$this->daterange[0] = date('d.m.y', strtotime('last month'));
		$this->daterange[1] = date('d.m.y');
		if ($request->has('daterange')) {
			$this->daterange = explode('-', $request->get('daterange'));
		}
		$this->currentMMC = null;
		$operatorIds = [];

		$patents = new ForeignerPatent;

		if (Auth::user()->hasRole('business.manager|accountant')) {
			$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
			$operatorIds = $mmcUsers;
			$this->currentMMC = Auth::user()->mmcListId()[0];
		} elseif (Auth::user()->hasRole('administrator|chief.accountant')) {
			if ($request->has('mmc')) {
				$mmcUsers = UserMMC::where('mmc_id', $request->get('mmc'))->pluck('user_id')->toArray();
				$operatorIds = $mmcUsers;
				$this->currentMMC = $request->get('mmc');
			} else {
				$operatorIds = User::get()->pluck(['id'])->toArray();
			}
 		}

		$patents = $patents->byDate($this->daterange[0], $this->daterange[1])->whereIn('operator_id', $operatorIds);
		if ($request->get('payments') != 1 || !$request->has('payments')) {
			$foreignersWithLaborService = ForeignerService::where('service_id', 52)->where('payment_status', 1)->pluck('foreigner_id')->toArray();
			$patents = $patents->whereIn('foreigner_id', $foreignersWithLaborService);
		}


		$this->patents = $patents;
	}

	public function index()
	{
		return view('export.patent.view', [
			'mmc' => $this->mmc,
			'daterange' => $this->daterange,
			'patents' => $this->patents->paginate(100),
		]);
	}

	public function export()
	{
		if ($this->currentMMC) {
			$mmc = str_replace(' ', '_', MMC::find($this->currentMMC)->name);
		} else {
			$mmc = 'Все_ММЦ';
		}

		$filename = 'Патенты_'.$mmc.'_'.$this->daterange[0].'-'.$this->daterange[1];
		$file = \Excel::create($filename, function($excel) {
		    $excel->sheet('New sheet', function($sheet) {
		        $sheet->loadView('export.patent.table', ['patents' => $this->patents->get(), 'export' => true]);
		    });
		})->store('csv');

		$file = Storage::disk('export')->get($filename.'.csv');
		$encoded = mb_convert_encoding($file, 'Windows-1251', 'UTF-8');
		Storage::disk('export_public')->put($filename.'.csv', $encoded);

		return response()->download(public_path().'/exports/'.$filename.'.csv');
	}
}
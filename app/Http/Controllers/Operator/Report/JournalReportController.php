<?php

namespace MMC\Http\Controllers\Operator\Report;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Auth;
use Helper;
use MMC\Models\Foreigner;
use MMC\Models\Service;
use MMC\Models\Client;
use MMC\Models\UserMMC;
use MMC\Models\ForeignerBlank;
use MMC\Models\ForeignerService;
use MMC\Models\ForeignerDms;
use MMC\Models\ForeignerIg;
use MMC\Models\ForeignerPatent;
use MMC\Models\ForeignerPatentChange;
use MMC\Models\ForeignerPatentRecertifying;
use MMC\Models\User;
use MMC\Models\MMC;
use Carbon\Carbon;
use Storage;

class JournalReportController extends Controller
{
	public $hosts;
	public $clients;
	public $operators;
	public $daterange;
	public $mmc;
	public $type;
	public $currentMMC;

	public function __construct(Request $request)
	{
		if ($request->has('recertifying')) {
			$this->journal = ForeignerPatentRecertifying::where('doc_status', 2)->orderBy('conf_at');
			$this->type = 0;
		} else {
			$this->journal = ForeignerPatent::where('doc_status', 2)->orderBy('conf_at');
			$this->type = 1;
		}

		$this->daterange[0] = date('d.m.y');
		$this->daterange[1] = date('d.m.y');
		$this->mmc = MMC::all();

		$users = User::orderBy('name', 'asc');
		if (Auth::user()->hasRole('administrator')) {
			if ($request->has('mmc')) {
				$mmcUsers = UserMMC::where('mmc_id', $request->get('mmc'))->pluck('user_id')->toArray();
				$users = $users->whereIn('id', $mmcUsers);
				$this->currentMMC = $request->get('mmc');
			}
 		} else {
 			$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
 			$users = $users->whereIn('id', $mmcUsers);
			$this->currentMMC = Auth::user()->mmcListId()[0];
 		}

 		$usersIds = [];
 		$users = $users->get();
 		foreach ($users as $user) {
			$usersIds[] = $user->id;
 		}

 		$this->journal = $this->journal->whereIn('uo_user', $usersIds);

		if ($request->has('daterange')) {
			$this->daterange = explode('-', $request->get('daterange'));
		}

		if ($request->has('user')) {
			$this->journal = $this->journal->where('uo_user', $request->get('user'));
		}

		if (count($this->daterange) == 1) {
			$this->journal = $this->journal->whereDate('conf_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
		} else {
			$this->journal = $this->journal->whereDate('conf_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
			$this->journal = $this->journal->whereDate('conf_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
		}

		$this->journal = $this->journal->get();
	}

	public function index(Request $request)
	{
		if ($request->has('export')) {
			return $this->export();
		}

		return view('report.journal.index', [
			'mmc' => $this->mmc,
			'daterange' => $this->daterange,
			'journal' => $this->journal,
		]);
	}

	public function print()
	{
		return view('report.journal.print', [
			'mmc' => $this->mmc,
			'daterange' => $this->daterange,
			'journal' => $this->journal,
		]);
	}

	public function export()
	{
		if ($this->currentMMC) {
			$mmc = str_replace(' ', '_', MMC::find($this->currentMMC)->name);
		} else {
			$mmc = 'Все_ММЦ';
		}

		if ($this->type == 0) {
			$type = 'Переоформление';
		} else {
			$type = 'Патент';
		}

		$filename = 'Журнал'.'_'.$type.'_'.$mmc.'_'.$this->daterange[0].'-'.$this->daterange[1];
		$file = \Excel::create($filename, function($excel) {
		    $excel->sheet('New sheet', function($sheet) {
		        $sheet->loadView('report.journal.table', [
		        	'journal' => $this->journal,
		        ]);
		    });
		})->store('csv');

		$file = Storage::disk('export')->get($filename.'.csv');
		$encoded = mb_convert_encoding($file, 'Windows-1251', 'UTF-8');
		Storage::disk('export_public')->put($filename.'.csv', $encoded);

		return response()->download(public_path().'/exports/'.$filename.'.csv');
	}

	public function approve($type, $id)
	{
		if ($type == 'patent') {
			$patent = ForeignerPatent::find($id);
		} else {
			$patent = ForeignerPatentRecertifying::find($id);
		}

		if ($patent->doc_status == 1) {
			$patent->doc_status = 2;
			$patent->conf_at = Carbon::now();
		} else {
			$patent->doc_status = 1;
			$patent->conf_at = null;
		}

		$patent->save();
		return redirect()->back();
	}

	public function remove($type, $id)
	{
		if ($type == 'patent') {
			$patent = ForeignerPatent::find($id);
		} else {
			$patent = ForeignerPatentRecertifying::find($id);
		}

		$patent->doc_status = 0;
		$patent->conf_at = Carbon::now();
		$patent->reg_at = Carbon::now();

		$patent->save();
		return redirect()->back();
	}
}
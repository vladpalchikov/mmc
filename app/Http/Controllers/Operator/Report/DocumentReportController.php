<?php

namespace MMC\Http\Controllers\Operator\Report;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Auth;
use Helper;
use MMC\Models\User;
use MMC\Models\MMC;
use MMC\Models\Service;
use MMC\Models\UserMMC;
use MMC\Models\Foreigner;
use MMC\Models\ForeignerService;
use MMC\Models\ForeignerDms;
use MMC\Models\ForeignerIg;
use MMC\Models\ForeignerPatent;
use MMC\Models\ForeignerPatentChange;
use MMC\Models\ForeignerPatentRecertifying;
use DB;

class DocumentReportController extends Controller
{
	public $operators;
	public $daterange;
	public $mmc;
	public $currentMMC;
	public $users;
	public $documents;

	public function __construct(Request $request)
	{
		$this->daterange[0] = date('d.m.y');
		$this->daterange[1] = date('d.m.y');
		$this->mmc = \MMC\Models\MMC::all();

		if ($request->has('daterange')) {
			$this->daterange = explode('-', $request->get('daterange'));
		}

		if ($request->has('type')) {
			$type = $request->get('type');
		}

		$users = User::orderBy('name', 'asc');
		if (Auth::user()->hasRole('business.manager|accountant')) {
			$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
			$users = $users->whereIn('id', $mmcUsers);
			$this->currentMMC = Auth::user()->mmcListId()[0];
		} elseif (Auth::user()->hasRole('administrator|chief.accountant')) {
			if ($request->has('mmc')) {
				$mmcUsers = UserMMC::where('mmc_id', $request->get('mmc'))->pluck('user_id')->toArray();
				$users = $users->whereIn('id', $mmcUsers);
				$this->currentMMC = $request->get('mmc');
			} else {
				$users = $users->where('mmc_id', MMC::first()->id);
				$this->currentMMC = MMC::first()->id;
			}
 		}

 		$usersIds = [];
 		$users = $users->get();
 		foreach ($users as $user) {
 			if ($user->hasRole('managertmsn|managertm|managermu|managermusn')) {
				$usersIds[] = $user->id;
 			}
 		}

 		$this->users = $users;

		$documentIg = new ForeignerIg;
		$documentPatent = new ForeignerPatent;
		$documentPatentchange = new ForeignerPatentChange;
		$documentPatentrecertifying = new ForeignerPatentRecertifying;

		if (Auth::user()->hasRole('managermu|managermusn|managertm|managertmsn')) {
			$documentIg = $documentIg->where('operator_id', Auth::user()->id);
			$documentPatent = $documentPatent->where('operator_id', Auth::user()->id);
			$documentPatentchange = $documentPatentchange->where('operator_id', Auth::user()->id);
			$documentPatentrecertifying = $documentPatentrecertifying->where('operator_id', Auth::user()->id);
		} else {
			$documentIg = $documentIg->whereIn('operator_id', $usersIds);
			$documentPatent = $documentPatent->whereIn('operator_id', $usersIds);
			$documentPatentchange = $documentPatentchange->whereIn('operator_id', $usersIds);
			$documentPatentrecertifying = $documentPatentrecertifying->whereIn('operator_id', $usersIds);
		}

		if ($request->has('daterange')) {
			if (count($this->daterange) == 1) {
				$documentIg = $documentIg->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
				$documentPatent = $documentPatent->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
				$documentPatentchange = $documentPatentchange->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
				$documentPatentrecertifying = $documentPatentrecertifying->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
			} else {
				$documentIg = $documentIg->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
				$documentPatent = $documentPatent->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
				$documentPatentchange = $documentPatentchange->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
				$documentPatentrecertifying = $documentPatentrecertifying->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));

				$documentIg = $documentIg->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
				$documentPatent = $documentPatent->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
				$documentPatentchange = $documentPatentchange->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
				$documentPatentrecertifying = $documentPatentrecertifying->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
			}
		} else {
			$documentIg = $documentIg->whereRaw('Date(created_at) = CURDATE()');
			$documentPatent = $documentPatent->whereRaw('Date(created_at) = CURDATE()');
			$documentPatentchange = $documentPatentchange->whereRaw('Date(created_at) = CURDATE()');
			$documentPatentrecertifying = $documentPatentrecertifying->whereRaw('Date(created_at) = CURDATE()');
		}

		if ($request->has('user')) {
			$documentIg = $documentIg->where('operator_id', $request->get('user'));
			$documentPatent = $documentPatent->where('operator_id', $request->get('user'));
			$documentPatentchange = $documentPatentchange->where('operator_id', $request->get('user'));
			$documentPatentrecertifying = $documentPatentrecertifying->where('operator_id', $request->get('user'));
		}

		$mergedDocuments = collect();
		if (!isset($type)) {
			$mergedDocuments = $mergedDocuments->merge($documentPatent->get());
			$mergedDocuments = $mergedDocuments->merge($documentPatentrecertifying->get());
			$mergedDocuments = $mergedDocuments->merge($documentPatentchange->get());
			$mergedDocuments = $mergedDocuments->merge($documentIg->get());
		} else {
			switch ($type) {
				case 1:
					$mergedDocuments = $mergedDocuments->merge($documentPatent->get());
				break;

				case 2:
					$mergedDocuments = $mergedDocuments->merge($documentPatentrecertifying->get());
				break;

				case 3:
					$mergedDocuments = $mergedDocuments->merge($documentPatentchange->get());
				break;

				case 4:
					$mergedDocuments = $mergedDocuments->merge($documentIg->get());
				break;
			}
		}

		$this->documents = $mergedDocuments;
	}

	public function index()
	{
		return view('report.document.index', [
			'mmc' => $this->mmc,
			'operators' => $this->operators,
			'users' => $this->users,
			'currentMMC' => $this->currentMMC,
			'daterange' => $this->daterange,
			'documents' => $this->documents,
		]);
	}

	public function print()
	{
		return view('report.document.print', [
			'mmc' => $this->mmc,
			'operators' => $this->operators,
			'users' => $this->users,
			'currentMMC' => $this->currentMMC,
			'daterange' => $this->daterange,
			'documents' => $this->documents,
		]);
	}
}
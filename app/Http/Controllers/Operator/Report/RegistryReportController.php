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
use Carbon\Carbon;

class RegistryReportController extends Controller
{
	public $hosts;
	public $clients;
	public $operators;
	public $daterange;
	public $mmc;
	public $users;

	public function __construct(Request $request)
	{
		$this->registry = ForeignerPatent::where('doc_status', '<>', 0)->orderBy('reg_at');
		$this->registryRecertifying = ForeignerPatentRecertifying::where('doc_status', '<>', 0)->orderBy('reg_at');
		$this->daterange[0] = date('d.m.y');
		$this->daterange[1] = date('d.m.y');
		$this->skip = $request->get('skip', 1);
		$this->limit = $request->get('limit', 0);
		$this->mmc = \MMC\Models\MMC::all();

		if ($this->skip == 0) {
			$this->skip = 1;
		}

		$users = User::orderBy('name', 'asc');
		if (Auth::user()->hasRole('administrator')) {
			if ($request->has('mmc')) {
				$mmcUsers = UserMMC::where('mmc_id', $request->get('mmc'))->pluck('user_id')->toArray();
				$users = $users->whereIn('id', $mmcUsers)->where('is_have_access_registry', true);
			} else {
				$users = $users->where('is_have_access_registry', true);
			}
 		} else {
 			$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
 			$users = $users->whereId('id', $mmcUsers)->where('is_have_access_registry', true);
 		}

 		$usersIds = [];
 		$users = $users->get();
 		$this->users = $users;
 		foreach ($users as $user) {
			$usersIds[] = $user->id;
 		}

 		$this->registry = $this->registry->whereIn('uo_user', $usersIds);
 		$this->registryRecertifying = $this->registryRecertifying->whereIn('uo_user', $usersIds);

		if ($request->has('daterange')) {
			$this->daterange = explode('-', $request->get('daterange'));
		}

		if ($request->has('user')) {
			$this->registry = $this->registry->where('uo_user', $request->get('user'));
			$this->registryRecertifying = $this->registryRecertifying->where('uo_user', $request->get('user'));
		}

		if (count($this->daterange) == 1) {
			$this->registry = $this->registry->whereDate('reg_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
			$this->registryRecertifying = $this->registryRecertifying->whereDate('reg_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
		} else {
			$this->registry = $this->registry->whereDate('reg_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
			$this->registry = $this->registry->whereDate('reg_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
			$this->registryRecertifying = $this->registryRecertifying->whereDate('reg_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
			$this->registryRecertifying = $this->registryRecertifying->whereDate('reg_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
		}

		$this->registry = $this->registry->get();
		$this->registry = $this->registry->merge($this->registryRecertifying->get())->sortBy('reg_at');
		if ($this->skip > 1) {
			$this->registry = $this->registry->slice($this->skip-1);
		}

		if ($this->limit > 1) {
			$this->registry = $this->registry->take($this->limit-$this->skip+1);
		}
	}

	public function index(Request $request)
	{
		if ($request->has('export')) {
			return $this->export();
		}

		return view('report.registry.index', [
			'mmc' => $this->mmc,
			'daterange' => $this->daterange,
			'registry' => $this->registry,
			'users' => $this->users,
			'skip' => $this->skip,
			'limit' => $this->limit,
		]);
	}

	public function print()
	{
		return view('report.registry.print', [
			'mmc' => $this->mmc,
			'daterange' => $this->daterange,
			'registry' => $this->registry,
			'users' => $this->users,
			'skip' => $this->skip,
		]);
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
		return $patent->doc_status;
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
		return $patent->id;
	}
}
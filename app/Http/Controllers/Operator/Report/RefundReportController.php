<?php

namespace MMC\Http\Controllers\Operator\Report;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Auth;
use Helper;
use MMC\Models\ForeignerService;
use MMC\Models\User;
use MMC\Models\UserMMC;

class RefundReportController extends Controller
{
	public $services;
	public $mmc;
	public $daterange;
	public $currentMMC;

	public function __construct(Request $request)
	{
		$this->mmc = \MMC\Models\MMC::all();
		$this->daterange[0] = date('d.m.y', strtotime('last month'));
		$this->daterange[1] = date('d.m.y');

		$services = ForeignerService::where('repayment_status', 3);

		if (Auth::user()->hasRole('business.manager|business.managerbg|accountant')) {
			$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
			$operatorIds = $mmcUsers;
			$this->currentMMC = Auth::user()->mmcListId()[0];
		} elseif (Auth::user()->hasRole('administrator|chief.accountant')) {
			if ($request->has('mmc')) {
				$mmcUsers = UserMMC::where('mmc_id', $request->get('mmc'))->pluck('user_id')->toArray();
				$operatorIds = $mmcUsers;
				$services = $services->whereIn('operator_id', $operatorIds);
				$this->currentMMC = $request->get('mmc');
			}
 		}

		if ($request->has('daterange')) {
			$this->daterange = explode('-', $request->get('daterange'));
			if (count($this->daterange) == 1) {
				$services = $services->whereDate('created_at', '=', Helper::formatDateForQuery($this->daterange[0]));
			} else {
				$services = $services->whereDate('created_at', '>=', Helper::formatDateForQuery($this->daterange[0]));
				$services = $services->whereDate('created_at', '<=', Helper::formatDateForQuery($this->daterange[1]));
			}
		} else {
			$services = $services->whereDate('created_at', '>=', Helper::formatDateForQuery($this->daterange[0]));
			$services = $services->whereDate('created_at', '<=', Helper::formatDateForQuery($this->daterange[1]));
		}

		$services = $services->orderBy('created_at', 'desc')->paginate(50);
        $services->appends($request->input())->links();
        $this->services = $services;
	}

	public function index()
	{
		return view('report.refund.index', [
			'mmc' => $this->mmc,
			'daterange' => $this->daterange,
			'services' => $this->services,
			'currentMMC' => $this->currentMMC,
		]);
	}

	public function print()
	{
		return view('report.refund.print', [
			'mmc' => $this->mmc,
			'daterange' => $this->daterange,
			'services' => $this->services,
			'currentMMC' => $this->currentMMC,
		]);
	}
}

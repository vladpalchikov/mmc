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
use MMC\Models\ForeignerBlank;
use MMC\Models\ForeignerService;
use MMC\Models\ForeignerDms;
use MMC\Models\ForeignerIg;
use MMC\Models\ForeignerPatent;
use MMC\Models\ForeignerPatentChange;
use MMC\Models\ForeignerPatentRecertifying;

class BlankReportController extends Controller
{
	public $hosts;
	public $clients;
	public $operators;
	public $daterange;
	
	public function __construct(Request $request)
	{
		$this->blanks = ForeignerBlank::orderBy('created_at');
		$this->daterange[0] = date('d.m.y', strtotime('last month'));
		$this->daterange[1] = date('d.m.y');

		if ($request->has('daterange')) {
			$this->daterange = explode('-', $request->get('daterange'));
		}

		if (count($this->daterange) == 1) {
			$this->blanks = $this->blanks->whereDate('created_at' , '=', Helper::formatDateForQuery($this->daterange[0]));
		} else {
			$this->blanks = $this->blanks->whereDate('created_at' , '>=', Helper::formatDateForQuery($this->daterange[0]));
			$this->blanks = $this->blanks->whereDate('created_at' , '<=', Helper::formatDateForQuery($this->daterange[1]));
		}
	}

	public function index()
	{
		return view('report.blank.index', [
			'operators' => $this->operators,
			'daterange' => $this->daterange,
			'blanks' => $this->blanks->get(),
		]);
	}

	public function print() 
	{
		return view('report.blank.print', [
			'operators' => $this->operators,
			'daterange' => $this->daterange,
			'blanks' => $this->blanks->get(),
		]);
	}
}
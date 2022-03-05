<?php

namespace MMC\Http\Controllers\Operator\Report;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;
use Helper;
use DB;
use MMC\Models\ForeignerQR;
use MMC\Models\Foreigner;
use MMC\Models\UserMMC;

class TaxReportController extends Controller
{
	public $clients;
	public $mmc;
	public $daterange;
	public $taxes;

	public function __construct(Request $request)
	{
		$this->mmc = \MMC\Models\MMC::all();
		$this->daterange[0] = date('d.m.y', strtotime('last month'));
		$this->daterange[1] = date('d.m.y');
		$this->qrs = [];
		$this->taxes = \MMC\Models\Tax::all();

		if (Auth::user()->hasRole('business.manager|accountant')) {
			$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
			$users = \MMC\Models\User::where('active', '=', 1)->whereIn('id', $mmcUsers)->orderBy('name', 'asc')->get();
		} else {
			$users = \MMC\Models\User::where('active', '=', 1)->orderBy('name', 'asc')->get();
		}

		$qrs = new ForeignerQR;

		if ($request->has('status')) {
			$qrs = $qrs->where('status', $request->get('status'));
		}

		if ($request->has('tax')) {
			if ($request->get('tax') == 'null') {
				$qrs = $qrs->whereNull('tax_id');
			} else {
				$qrs = $qrs->where('tax_id', $request->get('tax'));
			}
		}

		if ($request->has('search')) {
			if (strlen($request->get('search')) == 14) { // if txn_id
				$qrs = $qrs->where('txn_id', $request->get('search'));
			} else if (strlen($request->get('search')) == 10) { // if transaction
				$qrs = $qrs->where('transaction', $request->get('search'));
			} else {
				$qrs = $qrs->whereHas('foreigner', function ($query) use ($request) {
					$query->whereRaw('CONCAT_WS("",document_series,document_number)="'.$request->get('search').'"')
					->orWhere('document_number', $request->get('search'));
				});
			}
		}

		if (Auth::user()->hasRole('business.manager|accountant')) {
			$mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
			$operatorIds = $mmcUsers;
			$qrs = $qrs->whereIn('operator_id', $operatorIds);
		}

		if ($request->has('mmc')) {
			$mmcUsers = UserMMC::where('mmc_id', $request->get('mmc'))->pluck('user_id')->toArray();
			$operatorIds = $mmcUsers;
			$qrs = $qrs->whereIn('operator_id', $operatorIds);
		}

		if ($request->get('status') == 2) {
			if ($request->has('daterange')) {
				$this->daterange = explode('-', $request->get('daterange'));
				if (count($this->daterange) == 1) {
					$qrs = $qrs->whereDate('updated_at', '=', Helper::formatDateForQuery($this->daterange[0]));
				} else {
					$qrs = $qrs->whereDate('updated_at', '>=', Helper::formatDateForQuery($this->daterange[0]));
					$qrs = $qrs->whereDate('updated_at', '<=', Helper::formatDateForQuery($this->daterange[1]));
				}
			} else {
				$qrs = $qrs->whereDate('updated_at', '>=', Helper::formatDateForQuery($this->daterange[0]));
				$qrs = $qrs->whereDate('updated_at', '<=', Helper::formatDateForQuery($this->daterange[1]));
			}
		} else {
			if ($request->has('daterange')) {
				$this->daterange = explode('-', $request->get('daterange'));
				if (count($this->daterange) == 1) {
					$qrs = $qrs->whereDate('created_at', '=', Helper::formatDateForQuery($this->daterange[0]));
				} else {
					$qrs = $qrs->whereDate('created_at', '>=', Helper::formatDateForQuery($this->daterange[0]));
					$qrs = $qrs->whereDate('created_at', '<=', Helper::formatDateForQuery($this->daterange[1]));
				}
			} else {
				$qrs = $qrs->whereDate('created_at', '>=', Helper::formatDateForQuery($this->daterange[0]));
				$qrs = $qrs->whereDate('created_at', '<=', Helper::formatDateForQuery($this->daterange[1]));
			}
		}

		$this->qrs = $qrs->orderBy('status_datetime', 'desc');
	}

	public function index()
	{
		$this->qrs = $this->qrs->paginate(100);
		return view('report.tax.index', [
			'mmc' => $this->mmc,
			'daterange' => $this->daterange,
			'qrs' => $this->qrs,
			'taxes' => $this->taxes,
		]);
	}

	public function print()
	{
		$this->qrs = $this->qrs->paginate(100);
		return view('report.tax.print', [
			'mmc' => $this->mmc,
			'daterange' => $this->daterange,
			'qrs' => $this->qrs,
			'taxes' => $this->taxes,
		]);
	}

	public function unrecognize(Request $request)
	{
		$qrs = new ForeignerQR;

		if ($request->has('search')) {
			if (strlen($request->get('search')) == 14) { // if txn_id
				$qrs = $qrs->where('txn_id', $request->get('search'));
			} else if (strlen($request->get('search')) == 10) { // if transaction
				$qrs = $qrs->where('transaction', $request->get('search'));
			} else {
				$qrs = $qrs->whereHas('foreigner', function ($query) use ($request) {
					$query->whereRaw('CONCAT_WS("",document_series,document_number)="'.$request->get('search').'"');
				});
			}
		}
		$this->qrs = $qrs->whereNull('foreigner_id')->orderBy('status_datetime', 'desc')->paginate(100);
		return view('report.tax.unrecognize', [
			'mmc' => $this->mmc,
			'daterange' => $this->daterange,
			'qrs' => $this->qrs,
			'taxes' => $this->taxes,
		]);
	}

	public function link(Request $request)
	{
		if ($request->has('qr_id') && $request->has('ig_id')) {
			$qr = ForeignerQR::find($request->get('qr_id'));
			$qr->foreigner_id = $request->get('ig_id');
			$qr->save();
			return response()->json([
				'status' => 'success'
			]);
		}

		return response()->json([
			'status' => 'error'
		]);
	}

	public function parse(Request $request)
	{
		if ($request->has('qr_id')) {
            $qr = \MMC\Models\ForeignerQR::find($request->get('qr_id'));

            $names = explode(' / ', $qr->fio);

            if (count($names) < 2) {
                $names = explode(' ', $qr->fio);
            }

            $documents[0] = preg_replace('/[^a-zA-Zа-яА-Я]/', '', $qr->document);
            $documents[1] = preg_replace('/[^0-9]/', '', $qr->document);

            $data['surname'] = isset($names[0]) ? $names[0] : '';
            $data['name'] = isset($names[1]) ? $names[1] : '';
            $data['middle_name'] = isset($names[2]) ? $names[2] : '';
            $data['document_series'] = isset($documents[0]) ? $documents[0] : '';
            $data['document_number'] = isset($documents[1]) ? $documents[1] : '';
            $data['inn'] = $qr->inn;

            return response()->json($data);
        }
	}

	public function saveForeigner(Request $request)
	{
		if ($request->has('name')) {
			$foreigner = new Foreigner;
			$foreigner->name = $request->get('name');
			$foreigner->surname = $request->get('surname');
			$foreigner->middle_name = $request->get('middle_name');
			$foreigner->document_series = $request->get('document_series');
			$foreigner->document_number = $request->get('document_number');
			$foreigner->inn = $request->get('inn');
			$foreigner->operator_id = Auth::user()->id;
			$foreigner->save();

			if ($request->has('qr_id')) {
	            $foreignerQr = ForeignerQR::find($request->get('qr_id'));
	            $foreignerQr->foreigner_id = $foreigner->id;
	            $foreignerQr->save();
	        }

			return response()->json([
				'status' => 'saved'
			]);
		}

		return response()->json([
			'status' => 'error'
		]);
	}
}
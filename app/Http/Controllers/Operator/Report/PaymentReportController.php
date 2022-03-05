<?php

namespace MMC\Http\Controllers\Operator\Report;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;
use Helper;
use DB;
use MMC\Models\Foreigner;

class PaymentReportController extends Controller
{
	public $clients;
	public $mmc;
	public $daterange;
	public $taxes;
	public $stats = [];
	public $sum = [];

	public function __construct(Request $request)
	{
		$this->daterange[0] = date('d.m.y');
		$this->daterange[1] = date('d.m.y');
		$this->qrs = [];
		$this->taxes = \MMC\Models\Tax::all();

		$qrs = new \MMC\Models\ForeignerQR;
		$qrsUpdated = new \MMC\Models\ForeignerQR;
		$qrsReturned = new \MMC\Models\ForeignerQR;

		if ($request->has('status')) {
			$qrs = $qrs->where('status', $request->get('status'));
			$qrsUpdated = $qrsUpdated->where('status', $request->get('status'));
		}

		if ($request->has('tax')) {
			if ($request->get('tax') == 'null') {
				$qrs = $qrs->whereNull('tax_id');
				$qrsUpdated = $qrsUpdated->whereNull('tax_id');
			} else {
				$qrs = $qrs->where('tax_id', $request->get('tax'));
				$qrsUpdated = $qrsUpdated->where('tax_id', $request->get('tax'));
			}
		}

		if ($request->has('daterange')) {
			$this->daterange = explode('-', $request->get('daterange'));
			if (count($this->daterange) == 1) {
				$qrs = $qrs->whereDate('created_at', '=', Helper::formatDateForQuery($this->daterange[0]));
				$qrsUpdated = $qrsUpdated->whereDate('updated_at', '=', Helper::formatDateForQuery($this->daterange[0]));
			} else {
				$qrs = $qrs->whereDate('created_at', '>=', Helper::formatDateForQuery($this->daterange[0]));
				$qrs = $qrs->whereDate('created_at', '<=', Helper::formatDateForQuery($this->daterange[1]));

				$qrsUpdated = $qrsUpdated->whereDate('updated_at', '>=', Helper::formatDateForQuery($this->daterange[0]));
				$qrsUpdated = $qrsUpdated->whereDate('updated_at', '<=', Helper::formatDateForQuery($this->daterange[1]));
			}
		} else {
			$qrs = $qrs->whereDate('created_at', '>=', Helper::formatDateForQuery($this->daterange[0]));
			$qrs = $qrs->whereDate('created_at', '<=', Helper::formatDateForQuery($this->daterange[1]));

			$qrsUpdated = $qrsUpdated->whereDate('updated_at', '>=', Helper::formatDateForQuery($this->daterange[0]));
			$qrsUpdated = $qrsUpdated->whereDate('updated_at', '<=', Helper::formatDateForQuery($this->daterange[1]));
		}

		$qrsReturned = $qrsReturned->where('foreigner_id', '<>', null)->whereDate('created_at', '<', Helper::formatDateForQuery($this->daterange[0]));

		$this->qrs = $qrs->where('is_verified', true)->get();
		$qrsUpdated = $qrsUpdated->get();
		$this->stats['payers_count'] = 0;
		$this->stats['payers_count_with_inn'] = 0;
		$this->stats['payers_count_with_inn_percent'] = 0;
		$this->stats['payments_count'] = 0;
		$this->stats['payments_count_with_inn'] = 0;
		$this->stats['payments_count_with_inn_percent'] = 0;
		$this->stats['repeated_payments_count'] = 0;
		$this->stats['repeated_payments_count_percent'] = 0;
		$this->stats['repayments_count'] = 0;
		$this->stats['repayments_sum'] = 0;
		$this->stats['paid_sum'] = 0;
		$this->stats['paid_sum_avg'] = 0;
		$this->stats['tax_paid_sum'] = 0;
		$this->stats['agent_sum'] = 0;
		$this->stats['agent_sum_client'] = 0;
		$this->stats['returned_count'] = 0;
		$this->stats['returned_percent'] = 0;
		$this->stats['unrecognized'] = 0;


		$this->stats['unrecognized'] = $this->qrs->where('foreigner_id', null)->count();

		// Плательщики
		$this->stats['payers_count'] = $this->qrs->where('foreigner_id', '<>', null)->unique('foreigner_id')->count();
		$this->stats['payers_count_with_inn'] = Foreigner::whereIn('id', $this->qrs->where('foreigner_id', '<>', null)->pluck('foreigner_id'))->where('inn', '<>', 0)->whereNotNull('inn')->count();
		if ($this->stats['payers_count'] > 0) {
			$this->stats['payers_count_with_inn_percent'] = $this->stats['payers_count_with_inn'] / $this->stats['payers_count'] * 100;
		}

		// Платежи
		$this->stats['payments_count'] = $this->qrs->count();
		$this->stats['payments_count_with_inn'] = $this->stats['payments_count'] - $this->qrs->where('inn', 0)->count();
		if ($this->stats['payments_count'] > 0) {
			$this->stats['payments_count_with_inn_percent'] = $this->stats['payments_count_with_inn'] / $this->stats['payments_count'] * 100;
		}

		// Повторные платежи
		$this->stats['repeated_payments_count'] = $this->qrs->whereIn('foreigner_id', $qrsReturned->pluck('foreigner_id'))->unique('foreigner_id')->count();
		if ($this->qrs->where('foreigner_id', '<>', null)->count() > 0) {
			$this->stats['repeated_payments_count_percent'] = $this->stats['payments_count'] / $this->qrs->where('foreigner_id', '<>', null)->count();
		}

		// Возвраты
		$this->stats['repayments_count'] = $qrsUpdated->where('status', 2)->count();
		$this->stats['repayments_sum'] = $qrsUpdated->where('status', 2)->sum('sum_from');

		// Оплачено
		$this->stats['paid_sum'] = $this->qrs->where('status', 1)->sum('sum_from');
		if ($this->stats['payments_count'] > 0) {
			$this->stats['paid_sum_avg'] = $this->stats['paid_sum'] / $this->stats['payments_count'];
		}

		// Сумма налогов
		$this->stats['tax_paid_sum'] = $this->qrs->where('status', 1)->sum('sum');

		// Выручка агента
		$this->stats['agent_sum'] = $this->stats['paid_sum'] - $this->stats['tax_paid_sum'];
		if ($this->qrs->where('foreigner_id', '<>', null)->count() > 0) {
			$this->stats['agent_sum_client'] = $this->stats['paid_sum'] - $this->stats['tax_paid_sum'] / $this->stats['payers_count'];
		}

		// Вернувшиеся плательщики
		$this->stats['returned_count'] = $this->qrs->whereIn('foreigner_id', $qrsReturned->pluck('foreigner_id'))->unique('foreigner_id')->count();
		if ($this->qrs->where('foreigner_id', '<>', null)->count() > 0) {
			$this->stats['returned_percent'] = $this->stats['returned_count'] / $this->qrs->where('foreigner_id', '<>', null)->count() * 100;
		}

		foreach ($this->qrs->where('tax_id', 1) as $qr) {
			if (isset($this->sum[$qr->sum])) {
				$this->sum[$qr->sum]++;
			} else {
				$this->sum[$qr->sum] = 1;
			}
		}

		arsort($this->sum);
	}

	public function index()
	{
		return view('report.payment.index', [
			'daterange' => $this->daterange,
			'qrs' => $this->qrs,
			'taxes' => $this->taxes,
			'stats' => $this->stats,
			'sum' => $this->sum,
		]);
	}

	public function print()
	{
		return view('report.payment.print', [
			'daterange' => $this->daterange,
			'qrs' => $this->qrs,
			'taxes' => $this->taxes,
			'stats' => $this->stats,
			'sum' => $this->sum,
		]);
	}
}
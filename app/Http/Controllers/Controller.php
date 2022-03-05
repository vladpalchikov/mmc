<?php

namespace MMC\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function paginate($items, $perPage)
	{
	    $pageStart = request('page', 1);
	    $offSet    = ($pageStart * $perPage) - $perPage;
	    $itemsForCurrentPage = $items->slice($offSet, $perPage);

	    return new \Illuminate\Pagination\LengthAwarePaginator(
	        $itemsForCurrentPage, $items->count(), $perPage,
	        \Illuminate\Pagination\Paginator::resolveCurrentPage(),
	        ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
	    );
	}
}

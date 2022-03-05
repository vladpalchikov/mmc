<?php

namespace MMC\Http\Controllers\Operator;

use Illuminate\Http\Request;
use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use MMC\Models\Service;
use Auth;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $services = Service::orderBy('order', 'asc');

        if (Auth::user()->hasPermission('tm') || Auth::user()->hasPermission('mu') || Auth::user()->hasPermission('bg')) {
            $services = Service::orderBy('order', 'asc');
            $modules = [];
            if (Auth::user()->hasPermission('tm')) {
                $modules[] = 0;
            }

            if (Auth::user()->hasPermission('mu')) {
                $modules[] = 1;
            }

            if (Auth::user()->hasPermission('bg')) {
                $modules[] = 2;
            }

            $services = $services->whereIn('module', $modules);

            if ($request->has('active')) {
                $services = $services->where('status', '=', '0');
            } else {
                $services = $services->where('status', '=', '1');
            }

            $services = $services->get();
        }

        return view('operator.service.index', compact('services'));
    }
}

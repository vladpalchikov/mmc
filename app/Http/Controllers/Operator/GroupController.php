<?php

namespace MMC\Http\Controllers\Operator;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;
use MMC\Models\ForeignerServiceGroup;
use MMC\Models\Client;
use MMC\Models\ClientTransaction;
use Carbon\Carbon;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = ForeignerServiceGroup::orderBy('name')->get();
        return view('operator.client.index', compact('groups'));
    }

    
    public function show($id, Request $request)
    {
        $group = ForeignerServiceGroup::findOrFail($id);
        return view('operator.group.show', compact('group'));
    }
}

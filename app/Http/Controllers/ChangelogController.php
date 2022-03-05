<?php

namespace MMC\Http\Controllers;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use Auth;

class ChangelogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FormBuilder $formBuilder)
    {
        $updates = \MMC\Models\Update::orderBy('id', 'desc')->get();
        return view('changelog', compact('updates'));
    }
}

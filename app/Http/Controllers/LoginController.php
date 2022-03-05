<?php

namespace MMC\Http\Controllers;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use MMC\Models\UserMMC;
use Auth;

class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\LoginForm::class, [
            'method' => 'POST',
            'url' => '/login'
        ]);

        return view('login', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\LoginForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        if (Auth::attempt(['login' => $request->get('login'), 'password' => $request->get('password')])) {
            $mmc = \MMC\Models\MMC::find(Auth::user()->mmc_id);

            if (isset($mmc->ip) && !empty($mmc->ip)) {
                if (!Auth::user()->hasRole('admin|administrator|chief.accountant')) {
                    $ips = array_map('trim', (explode(',', $mmc->ip)));
                    if (!in_array($request->ip(), $ips)) {
                        Auth::logout();
                        return redirect()->back()->withErrors(['wrong' => 'С этого IP адреса ('.$request->ip().') доступ запрещен'])->withInput();
                    }
                }
            }

            if (Auth::user()->hasRole('admin')) {
                return redirect()->intended('/users');
            }

            if (Auth::user()->hasRole('administrator|managertm|managertmsn|managerbg|business.manager|business.managerbg')) {
                return redirect()->intended('/operator/current');
            }

            if (Auth::user()->hasRole('administrator|managermu|managermusn')) {
                return redirect()->intended('/operator/muservices');
            }

            if (Auth::user()->hasRole('cashier|accountant|chief.accountant')) {
                return redirect()->intended('/operator/current');
            }
        } else {
            return redirect()->back()->withErrors(['wrong' => 'Неправильный логин или пароль'])->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}

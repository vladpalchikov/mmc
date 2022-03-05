<?php

namespace MMC\Http\Controllers\Admin;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Kris\LaravelFormBuilder\FormBuilder;
use MMC\Models\User;
use MMC\Models\UserMMC;
use MMC\Models\MMC;
use Ultraware\Roles\Models\Role;
use Auth;
use Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::orderBy('id', 'desc');
        $mmcUsers = UserMMC::where('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
        if (Auth::user()->hasRole('business.manager|business.managerbg')) {
            $users = $users->whereIn('id', $mmcUsers);
        }

        $mmc = MMC::whereIn('id', Auth::user()->mmcListId())->get();

        if ($request->has('banned')) {
            $users = $users->where('active', '=', '0');
        } else {
            $users = $users->where('active', '=', '1');
        }

        if ($request->has('mmc')) {
            $mmcUsers = UserMMC::where('mmc_id', $request->get('mmc'))->pluck('user_id')->toArray();
            $users = $users->whereIn('id', $mmcUsers);
        } else {
            $mmcUsers = UserMMC::whereIn('mmc_id', Auth::user()->mmcListId())->pluck('user_id')->toArray();
            $users = $users->whereIn('id', $mmcUsers);
        }

        if (!Auth::user()->hasRole('admin')) {
            $users = $users->where('id', '<>', Auth::user()->id);
        }

        return view('admin.user.index', compact('users', 'mmc'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\UserForm::class, [
            'method' => 'POST',
            'url' => '/users'
        ]);

        return view('admin.user.create', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(\MMC\Http\Requests\StoreUser $request, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\UserForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $user = new User;
        $user->fill($request->all());
        $user->mmc_id = $request->get('mmc_id')[0];
        $user->password = bcrypt($user->password);
        $user->active = $request->has('active') ? 1 : 0;
        $user->is_have_access_strict_report = $request->has('is_have_access_strict_report') ? 1 : 0;
        $user->is_have_access_registry = $request->has('is_have_access_registry') ? 1 : 0;
        $user->save();

        foreach ($request->get('role') as $role) {
            $user->attachRole($role);
        }

        if ($request->has('mmc_id')) {
            foreach ($request->get('mmc_id') as $mmc_id) {
                $userMMC = new UserMMC;
                $userMMC->mmc_id = $mmc_id;
                $userMMC->user_id = $user->id;
                $userMMC->save();
            }
        }

        return redirect('/users');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, FormBuilder $formBuilder)
    {
        $user = User::find($id);
        $user->password = '';
        $form = $formBuilder->create(\MMC\Forms\UserForm::class, [
            'method' => 'PUT',
            'url' => '/users/'.$user->id,
            'model' => $user
        ]);

        return view('admin.user.create', compact('form', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, FormBuilder $formBuilder)
    {
        $form = $formBuilder->create(\MMC\Forms\UserForm::class);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        $user = User::find($id);
        $password = $user->password;
        $user->fill($request->all());
        $user->mmc_id = $request->get('mmc_id')[0];
        $user->active = $request->has('active') ? 1 : 0;
        $user->is_have_access_strict_report = $request->has('is_have_access_strict_report') ? 1 : 0;
        $user->is_have_access_registry = $request->has('is_have_access_registry') ? 1 : 0;

        if (empty($request->get('password'))) {
            $user->password = $password;
        } else {
            $user->password = bcrypt($request->get('password'));
        }

        $user->save();

        $user->detachAllRoles();
        $user->detachAllPermissions();

        if ($request->has('role')) {
            foreach ($request->get('role') as $role) {
                $user->attachRole($role);
            }
        }

        if ($request->has('permission')) {
            foreach ($request->get('permission') as $permission) {
                $user->attachPermission($permission);
            }
        }

        if ($request->has('mmc_id')) {
            UserMMC::where('user_id', $user->id)->delete();
            foreach ($request->get('mmc_id') as $mmc_id) {
                $userMMC = new UserMMC;
                $userMMC->mmc_id = $mmc_id;
                $userMMC->user_id = $user->id;
                $userMMC->save();
            }
        } else {
            UserMMC::where('user_id', $user->id)->delete();
            $userMMC = new UserMMC;
            $userMMC->mmc_id = 0;
            $userMMC->user_id = $user->id;
            $userMMC->save();
        }

        return redirect('/users');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
    }

    public function impersonate($id)
    {
        $user = User::find($id);
        Auth::user()->setImpersonating(Auth::user()->id);
        Auth::login($user);
        if ($user->hasRole('admin')) {
            return redirect()->intended('/users');
        }

        if ($user->hasRole('administrator|managertm|managertmsn|business.manager|managerbg|business.managerbg')) {
            return redirect()->intended('/operator/current');
        }

        if ($user->hasRole('administrator|managermu|managermusn')) {
            return redirect()->intended('/operator/muservices');
        }

        if ($user->hasRole('cashier|accountant|chief.accountant')) {
            return redirect()->intended('/operator/current');
        }
    }

    public function stopImpersonate()
    {
        $admin = User::find(Auth::user()->getImpersonating());
        Auth::user()->stopImpersonating();
        Auth::login($admin);
        return redirect('/users');
    }
}

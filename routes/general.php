<?php

Route::get('/citizenship', function() {
    $citizenshipData = (array) json_decode(file_get_contents(app_path('Library/XML/Citizenship.json')));

    foreach ($citizenshipData as $item) {
        $citizenship[$item->key] = $item->value;
    }

    dd($citizenship);
});

Route::get('/', function() {
    if (Auth::check()) {
    	if (Auth::user()->hasRole('admin')) {
            return redirect()->intended('/users');
        }

        if (Auth::user()->hasRole('administrator|managermu|managermusn')) {
            return redirect()->intended('/operator/muservices');
        } else {
            return redirect()->intended('/operator/current');
        }
    } else {
        return redirect()->intended('/login');
    }
});

Route::resource('/login', 'LoginController');
Route::resource('/operator/login', 'LoginController');
Route::get('/logout', 'LoginController@logout');
Route::get('/changelog', 'ChangelogController@index');

Route::get('/users/stop', 'Admin\UserController@stopImpersonate');
Route::resource('users', 'Admin\UserController');
Route::get('/users/{id}/impersonate', 'Admin\UserController@impersonate');
Route::resource('services', 'Admin\ServiceController');
Route::get('blanks/{id}/file', 'Admin\BlankController@file');
Route::resource('blanks', 'Admin\BlankController');

Route::group(['prefix' => 'ajax'], function() {
    Route::get('/innlock', 'AjaxController@checkInnLock');
    Route::get('/unlockinn', 'AjaxController@unlockInn');
    Route::get('/clients', 'AjaxController@clients');
});

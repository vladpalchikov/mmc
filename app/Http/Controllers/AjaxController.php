<?php

namespace MMC\Http\Controllers;

use Illuminate\Http\Request;

use MMC\Http\Requests;
use MMC\Http\Controllers\Controller;
use Auth;
use MMC\Models\Setting;
use MMC\Models\Client;

class AjaxController extends Controller
{
    public function checkInnLock()
    {
        return Setting::where('name', 'inn_lock')->first()->value;
    }

    public function unlockInn()
    {
        Setting::saveSetting('inn_lock', 'false');
    }

    public function clients(Request $request)
    {
        if ($request->has('term')) {
            $clients = Client::where('name', 'LIKE', ''.$request->get('term').'%')->where('is_archived', false)->orderBy('name')->limit(20)->get();
            $data = [];
            foreach ($clients as $client) {
                $data[] = ['id' => $client->id, 'text' => $client->name.' - '.$client->organization_address];
            }
            return $data;
        } else {
            return ['error' => 'Search is required'];
        }
    }
}

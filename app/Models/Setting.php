<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public static function saveSetting($name, $value)
    {
    	if (Setting::where('name', $name)->count() > 0) {
    		$setting = Setting::where('name', $name)->first();
    		$setting->value = $value;
    	} else {
    		$setting = new Setting;
    		$setting->name = $name;
    		$setting->value = $value;
    	}

    	$setting->save();
    }

    public static function getSetting($name)
    {
        if (Setting::where('name', $name)->count() > 0) {
            $setting = Setting::where('name', $name)->first();
            return $setting->value;
        }

        return false;
    }
}

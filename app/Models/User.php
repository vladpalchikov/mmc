<?php

namespace MMC\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Ultraware\Roles\Traits\HasRoleAndPermission;
use Ultraware\Roles\Contracts\HasRoleAndPermission as HasRoleAndPermissionContract;
use Illuminate\Notifications\Notifiable;
use Session;
use MMC\Models\Foreigner;

class User extends Authenticatable implements HasRoleAndPermissionContract
{
    use HasRoleAndPermission, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login', 'password', 'name', 'active', 'mmc_id', 'phone', 'is_have_access_strict_report', 'is_have_access_registry'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function routeNotificationForSlack() {
        return 'https://hooks.slack.com/services/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';
    }

    public function mmc()
    {
        return $this->hasOne('MMC\Models\MMC', 'id', 'mmc_id');
    }

    public function mmcList()
    {
        return $this->hasMany('MMC\Models\UserMMC', 'user_id', 'id');
    }

    public function dms()
    {
        return $this->hasMany('MMC\Models\ForeignerDms', 'operator_id', 'id');
    }

    public function ig()
    {
        return $this->hasMany('MMC\Models\ForeignerIg', 'operator_id', 'id');
    }

    public function patent()
    {
        return $this->hasMany('MMC\Models\ForeignerPatent', 'operator_id', 'id');
    }

    public function patentchange()
    {
        return $this->hasMany('MMC\Models\ForeignerPatentChange', 'operator_id', 'id');
    }

    public function patentrecertifying()
    {
        return $this->hasMany('MMC\Models\ForeignerPatentRecertifying', 'operator_id', 'id');
    }

    public function countDocuments()
    {
        $count = 0;
        $count += $this->dms->count();
        $count += $this->ig->count();
        $count += $this->patent->count();
        $count += $this->patentchange->count();
        $count += $this->patentrecertifying->count();
        return $count;
    }

    public function setImpersonating($id)
    {
        Session::put('impersonate', $id);
    }

    public function stopImpersonating()
    {
        Session::forget('impersonate');
    }

    public function isImpersonating()
    {
        return Session::has('impersonate');
    }

    public function getImpersonating()
    {
        return Session::get('impersonate');
    }

    public function mmcListId()
    {
        if (UserMMC::where('user_id', $this->id)->count() > 0) {
            if (UserMMC::where('user_id', $this->id)->count() == 1) {
                if (UserMMC::where('user_id', $this->id)->first()->mmc_id == 0) {
                    $mmc = MMC::all()->pluck('id')->toArray();
                    $mmc[count($mmc)+1] = 0;
                    return $mmc;
                }
            }
            return UserMMC::where('user_id', $this->id)->pluck('mmc_id')->toArray();
        } else {
            $mmc = MMC::all()->pluck('id')->toArray();
            $mmc[count($mmc)+1] = 0;
            return $mmc;
        }
    }
}

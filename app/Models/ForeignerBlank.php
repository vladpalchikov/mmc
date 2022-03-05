<?php

namespace MMC\Models;

use Illuminate\Database\Eloquent\Model;

class ForeignerBlank extends Model
{
    public function operator()
    {
        return $this->hasOne('MMC\Models\User', 'id', 'operator_id');
    }

    public function foreigner()
    {
        return $this->hasOne('MMC\Models\Foreigner', 'id', 'foreigner_id');
    }

    public function getNumber()
    {
    	if (strlen($this->number) == 1) { $number = '0000'.$this->number; }
    	if (strlen($this->number) == 2) { $number = '000'.$this->number; }
    	if (strlen($this->number) == 3) { $number = '00'.$this->number; }
    	if (strlen($this->number) == 4) { $number = '0'.$this->number; }
    	if (strlen($this->number) == 5) { $number = $this->number; }
    	
    	return $this->created_at->format('Y').$this->created_at->format('m').$number;
    }
}

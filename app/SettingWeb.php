<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SettingWeb extends Model
{

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'province_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'city_id');
    }

    public function getLogoUrlAttribute()
    {
        if($this->logo == '') {
            return asset('img/yourlogo.png');
        } else {
            return url(Storage::url($this->logo));
        }
    }

    public function getLogoRectUrlAttribute()
    {
        if($this->logo_rect == '') {
            return asset('img/yourlogo.png');
        } else {
            return url(Storage::url($this->logo_rect));
        }
    }

    protected $guarded = ['id'];
    protected $appends = [
        'logo_url',
        'logo_rect_url',
    ];
    protected $hidden = ['ipaymu_api', 'ipaymu_va'];
}

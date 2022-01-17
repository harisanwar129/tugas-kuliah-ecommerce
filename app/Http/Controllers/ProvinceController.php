<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProvinceController extends Controller
{
    public function fetch($province_id)
    {
        return DB::table('cities')->where('province_id', $province_id)->get();
    }
}

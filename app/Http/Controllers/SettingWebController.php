<?php

namespace App\Http\Controllers;

use App\City;
use App\Province;
use App\SettingWeb;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class SettingWebController extends Controller
{
    public function setting(Request $request)
    {
        $setting = SettingWeb::where('id', 1)->with(['city'])->first();
        $provinces = Province::all();
        if ($setting == null)
        {
            $setting = new SettingWeb();
            $setting->save();
        }

        $cities = null;
        if ($setting->city != null) {
            $cities = City::where('province_id', $setting->city->province->province_id)->get();
        }

        $data = [
            'title' => 'Setting Aplikasi',
            'setting' => $setting,
            'provinces' => $provinces,
            'cities' => $cities,
        ];

        return view('dashboard.setting', $data);
    }

    public function setting_update(Request $request)
    {
        $request->validate([
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,bmp', 'between:0,4048'],
            'logo_rect' => ['nullable', 'file', 'mimes:jpg,jpeg,png,bmp', 'between:0,4048'],
            'app_name' => ['required', 'max:30'],
            'color' => ['nullable'],
            'footer_text' => ['nullable'],
            'ipaymu_api' => ['required'],
            'ipaymu_va' => ['required'],
            'ipaymu_url' => ['required'],
            'rajaongkir_api_key' => ['nullable'],
            'city_from' => ['required', 'integer']
        ]);

        if ($request->kode_rahasia != env('KODE_RAHASIA')) {
            return redirect()->back()->with('error', 'Kode Rahasia salah!');
        }

        $setting = SettingWeb::findOrFail(1);
        if ($request->hasFile('logo')) {
            if ($setting->logo != '') {
                Storage::delete($setting->logo);
            }
            $filename = Str::random(32) . '.' . $request->file('logo')->getClientOriginalExtension();
            $logo_file_path = $request->file('logo')->storeAs('public/uploads', $filename);
        }
        if ($request->hasFile('logo_rect')) {
            if ($setting->logo_rect != '') {
                Storage::delete($setting->logo_rect);
            }
            $filename = Str::random(32) . '.' . $request->file('logo_rect')->getClientOriginalExtension();
            $logo_rect_file_path = $request->file('logo_rect')->storeAs('public/uploads', $filename);
        }
        $setting->logo = isset($logo_file_path) ? $logo_file_path : $setting->logo;
        $setting->logo_rect = isset($logo_rect_file_path) ? $logo_rect_file_path : $setting->logo_rect;
        $setting->color = $request->color;
        $setting->app_name = $request->app_name;
        $setting->footer_text = $request->footer_text;
        $setting->ipaymu_api = $request->ipaymu_api;
        $setting->ipaymu_va = $request->ipaymu_va;
        $setting->ipaymu_url = $request->ipaymu_url;
        $setting->rajaongkir_api_key = $request->rajaongkir_api_key;
        $setting->city_id = $request->city_from;
        $setting->save();

        return redirect()->back()->with('success', 'Setting berhasil diupdate');
    }
}

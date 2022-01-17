<?php

use App\City;
use App\Province;
use Illuminate\Database\Seeder;
use Kavist\RajaOngkir\Facades\RajaOngkir;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinceList = RajaOngkir::provinsi()->all();
        foreach ($provinceList as $province) {
            Province::create([
                'province_id' => $province['province_id'],
                'title' => $province['province']
            ]);

            $cityList = RajaOngkir::kota()->dariProvinsi($province['province_id'])->get();
            foreach ($cityList as $city) {
                City::create([
                    'province_id' => $province['province_id'],
                    'city_id' => $city['city_id'],
                    'title' => $city['city_name']
                ]);
            }
        }
    }
}

<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingWebSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setting_webs')->insert([
            'logo' => '',
            'color' => '#A81D86',
            'app_name' => 'PasarMerce',
            'footer_text' => '@ 2021 PasarMerce',
            'ipaymu_api' => '',
            'ipaymu_va' => '',
            'ipaymu_url' => '',
            'ipaymu_production' => false,
        ]);
    }
}

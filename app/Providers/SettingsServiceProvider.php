<?php

namespace App\Providers;

use App\SettingWeb;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Laravel >= 5.2, use 'lists' instead of 'pluck' for Laravel <= 5.1
        if(Schema::hasTable('setting_webs')) {
            $setting = SettingWeb::where('id', 1)->first();
            if ($setting == null)
            {
                $setting = new SettingWeb([
                    'logo' => '',
                    'color' => '#A81D86',
                    'app_name' => 'PasarMerce',
                    'footer_text' => '@ 2021 PasarMerce',
                    'ipaymu_api' => '',
                    'ipaymu_va' => '',
                    'ipaymu_url' => '',
                    'ipaymu_production' => false,
                ]);
                $setting->save();
            }
        } else {
            $setting = [];
        }
        config()->set('setting', $setting);
    }
}

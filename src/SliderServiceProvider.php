<?php

namespace Tir\Slider;


use Illuminate\Support\ServiceProvider;


class SliderServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */

    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadRoutesFrom(__DIR__.'/Routes/admin.php');

        $this->loadMigrationsFrom(__DIR__ .'/Database/Migrations');

        $this->loadViewsFrom(__DIR__.'/Resources/Views', 'slider');

        $this->loadTranslationsFrom(__DIR__.'/Resources/Lang/', 'slider');

    }
}

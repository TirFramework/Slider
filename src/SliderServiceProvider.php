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
        $this->loadMigrationsFrom(__DIR__ .'/Database/Migrations');

        if (! config('app.installed')) {
            return;
        }
        $this->loadRoutesFrom(__DIR__.'/Routes/admin.php');
        $this->loadViewsFrom(__DIR__.'/Resources/Views', 'slider');
        $this->loadTranslationsFrom(__DIR__.'/Resources/Lang/', 'slider');
        $this->loadTranslationsFrom(__DIR__.'/Resources/Lang/', 'sliderSlide');
        $this->adminMenu();
    }

    private function adminMenu()
    {
        $menu = resolve('AdminMenu');
        $menu->item('system')->title('slider::panel.system')->link('#')->add();
        $menu->item('system.slider')->title('slider::panel.slider')->link('#')->add();
        $menu->item('system.slider.slide_set')->title('slider::panel.slide_set')->route('slider.index')->add();
        $menu->item('system.slider.slides')->title('slider::panel.slides')->route('sliderSlide.index')->add();

    }
}

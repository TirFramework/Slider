<?php


// Add web middleware for use Laravel feature
Route::group(['middleware' => 'web'], function () {

    //add admin prefix and middleware for admin area to user module
    Route::group(['prefix' => 'admin', 'middleware' => 'IsAdmin'], function () {
        Route::resource('/slider', 'Tir\Slider\Controllers\AdminSliderController');
        Route::resource('/sliderSlide', 'Tir\Slider\Controllers\AdminSliderSlideController');
    });

});
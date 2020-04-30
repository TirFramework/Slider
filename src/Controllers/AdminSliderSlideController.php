<?php

namespace Tir\Slider\Controllers;

use Tir\Crud\Controllers\CrudController;
use Tir\Slider\Entities\SliderSlide;

class AdminSliderSlideController extends CrudController
{
    protected $model = SliderSlide::Class;

}

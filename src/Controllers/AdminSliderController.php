<?php

namespace Tir\Slider\Controllers;

use Tir\Slider\Entities\Slider;
use Tir\Crud\Controllers\CrudController;

class AdminSliderController extends CrudController
{
    protected $model = Slider::Class;

}

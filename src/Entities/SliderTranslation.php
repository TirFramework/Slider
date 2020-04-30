<?php

namespace Tir\Slider\Entities;

use Tir\Crud\Support\Eloquent\TranslationModel;


class SliderTranslation extends TranslationModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];
}

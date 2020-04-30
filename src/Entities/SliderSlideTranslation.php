<?php

namespace Tir\Slider\Entities;

use Tir\Crud\Support\Eloquent\TranslationModel;


class SliderSlideTranslation extends TranslationModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file',
        'caption_1',
        'caption_2',
        'caption_3',
        'call_to_action_text'
    ];
}

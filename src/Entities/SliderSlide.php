<?php

namespace Tir\Slider\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\Cache;
use Tir\Crud\Support\Eloquent\CrudModel;

class SliderSlide extends CrudModel
{
    //Additional trait insert here

    use Translatable;

    /**
     * The attribute show route name
     * and we use in fieldTypes and controllers
     *
     * @var string
     */
    public static $routeName = 'sliderSlide';


    public $table = 'slider_slides';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slider_id',
        'options',
        'call_to_action_url',
        'open_in_new_window',
        'position'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
        'open_in_new_window' => 'boolean'
        ];


    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatedAttributes = [
        'file',
        'caption_1',
        'caption_2',
        'caption_3',
        'call_to_action_text',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];


    /**
     * This function return array for validation
     *
     * @return array
     */
    public function getValidation()
    {
        return [
            'file' => 'required',
        ];
    }


    /**
     * This function return an object of field
     * and we use this for generate admin panel page
     * @return Object
     */
    public function getFields()
    {
        $fields = [
            [
                'name' => 'basic_information',
                'type' => 'group',
                'visible'    => 'ce',
                'tabs'=>  [
                    [
                        'name'  => 'slide_information',
                        'type'  => 'tab',
                        'visible'    => 'ce',
                        'fields' => [
                            [
                                'name'       => 'id',
                                'type'       => 'text',
                                'visible'    => 'io',
                            ],
                            [
                                'name'      => 'slider_id',
                                'display'   => 'slider',
                                'type'      => 'relation',
                                'relation'  => ['slider', 'name'],
                                'visible'   => 'ce',
                            ],
                            [
                                'name'      => 'file',
                                'type'      => 'image',
                                'visible'   => 'ce',
                            ],
                            [
                                'name'      => 'caption_1',
                                'type'      => 'text',
                                'visible'   => 'ice',
                            ],
                            [
                                'name'      => 'caption_2',
                                'type'      => 'text',
                                'visible'   => 'ce',
                            ],
                            [
                                'name'      => 'caption_3',
                                'type'      => 'text',
                                'visible'   => 'ce',
                            ],
                            [
                                'name'      => 'call_to_action_text',
                                'type'      => 'text',
                                'visible'   => 'ce',
                            ],
                            [
                                'name'      => 'call_to_action_url',
                                'type'      => 'text',
                                'visible'   => 'ce',
                            ],
                            [
                                'name'      => 'options[caption_1][delay]',
                                'type'      => 'text',
                                'visible'   => 'ce',
                            ],
                            [
                                'name'      => 'options[caption_1][effect]',
                                'type'      => 'text',
                                'visible'   => 'ce',
                            ],
                            [
                                'name'      => 'options[caption_2][delay]',
                                'type'      => 'text',
                                'visible'   => 'ce',
                            ],
                            [
                                'name'      => 'options[caption_2][effect]',
                                'type'      => 'text',
                                'visible'   => 'ce',
                            ],
                            [
                                'name'      => 'options[caption_3][delay]',
                                'type'      => 'text',
                                'visible'   => 'ce',
                            ],
                            [
                                'name'      => 'options[caption_3][effect]',
                                'type'      => 'text',
                                'visible'   => 'ce',
                            ],
                            [
                                'name'      => 'options[call_to_action][delay]',
                                'type'      => 'text',
                                'visible'   => 'ce',
                            ],
                            [
                                'name'      => 'options[call_to_action][effect]',
                                'type'      => 'text',
                                'visible'   => 'ce',
                            ],

                            [
                                'name'       => 'open_in_new_window',
                                'type'       => 'select',
                                'data'       => ['1'=>trans('slider::panel.yes'),'0'=>trans('slider::panel.no')],
                                'visible'    => 'ce',
                            ],
                            [
                                'name'       => 'position',
                                'type'       => 'position',
                                'visible'    => 'ce',
                            ],


                        ]
                    ]
                ]
            ]
        ];
        return json_decode(json_encode($fields));
    }

    //Additional methods //////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

//        static::saved(function ($slider) {
//            $slider->saveSlides(request('slides', []));
//            $slider->clearCache();
//        });
    }

    public function clearCache()
    {
        Cache::tags(["sliders.{$this->id}"])->flush();
    }

    public static function findWithSlides($id)
    {
        if (is_null($id)) {
            return;
        }

        return Cache::tags(["sliders.{$id}"])
            ->rememberForever("sliders.{$id}:" . locale(), function () use ($id) {
                return static::with('slides')->find($id);
            });
    }

    public function getAutoplaySpeedAttribute($autoplaySpeed)
    {
        return $autoplaySpeed ?: 3000;
    }

    /**
     * Save slides for the slider.
     *
     * @param array $slides
     * @return void
     */
    public function saveSlides($slides)
    {
        $ids = $this->getDeleteCandidates($slides);

        if ($ids->isNotEmpty()) {
            $this->slides()->whereIn('id', $ids)->delete();
        }

        foreach (array_reset_index($slides) as $index => $slide) {
            $this->slides()->updateOrCreate(
                ['id' => $slide['id']],
                $slide + ['position' => $index]
            );
        }
    }

    private function getDeleteCandidates($slides = [])
    {
        return $this->slides()
            ->pluck('id')
            ->diff(array_pluck($slides, 'id'));
    }



    //Relations methods ///////////////////////////////////////////////////////////////////////////////////////////////

    public function slider()
    {
        return $this->belongsTo(Slider::class);
    }

}

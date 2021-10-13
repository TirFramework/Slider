<?php

namespace Tir\Slider\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Support\Facades\Cache;
use Tir\Crud\Support\Eloquent\BaseScaffold;
use Tir\Crud\Support\Facades\Crud;

class Slider extends BaseScaffold
{
    //Additional trait insert here

    use Translatable;

    /**
     * The attribute show route name
     * and we use in fieldTypes and controllers
     *
     * @var string
     */
    public static $routeName = 'slider';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','autoplay', 'autoplay_speed', 'arrows', 'fade'];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];


    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatedAttributes = ['name'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations', 'slides'];


    /**
     * This function return array for validation
     *
     * @return array
     */
    public function getValidation()
    {
        return [
            'name' => 'required',
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
                        'name'  => 'slider_information',
                        'type'  => 'tab',
                        'visible'    => 'ce',
                        'fields' => [
                            [
                                'name'       => 'id',
                                'type'       => 'text',
                                'visible'    => 'io',
                            ],
                            [
                                'name'      => 'name',
                                'type'      => 'text',
                                'visible'   => 'ice',
                            ],
                            [
                                'name'       => 'autoplay',
                                'type'       => 'select',
                                'data'       => ['1'=>trans('slider::panel.yes'),'0'=>trans('slider::panel.no')],
                                'visible'    => 'ce',
                            ],
                            [
                                'name'       => 'autoplay_speed',
                                'type'       => 'number',
                                'visible'    => 'ce',
                            ],
                            [
                                'name'       => 'arrows',
                                'type'       => 'select',
                                'data'       => ['1'=>trans('slider::panel.yes'),'0'=>trans('slider::panel.no')],
                                'visible'    => 'ce',
                            ],
                            [
                                'name'       => 'dots',
                                'type'       => 'select',
                                'data'       => ['1'=>trans('slider::panel.yes'),'0'=>trans('slider::panel.no')],
                                'visible'    => 'ce',
                            ],


                        ]
                    ]
                ]
            ]
        ];
        return $fields;}

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
            ->rememberForever("sliders.{$id}:" . Crud::locale(), function () use ($id) {
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

    public function slides()
    {
        return $this->hasMany(SliderSlide::class)->orderBy('position');
    }

}

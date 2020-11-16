<?php


namespace GeoSot\BaseAdmin\App\Providers;

use Illuminate\Support\ServiceProvider;
use Intervention\Image\Image;
use Plank\Mediable\Facades\ImageManipulator;
use Plank\Mediable\ImageManipulation;


class ImageManipulatorServiceProvider extends ServiceProvider
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
        ImageManipulator::defineVariant('thumb',
            ImageManipulation::make(function (Image $image) {
                $image->fit(192, 108);
            })->toPngFormat()->setOutputQuality(70)
        );
    }
}

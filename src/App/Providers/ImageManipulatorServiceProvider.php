<?php


namespace GeoSot\BaseAdmin\App\Providers;

use GeoSot\BaseAdmin\App\Models\Media\Medium;
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
        ImageManipulator::defineVariant(Medium::VARIANT_NAME_THUMB,
            ImageManipulation::make(function (Image $image) {
                $image->widen(120);
            })
                ->outputPngFormat()
                ->setOutputQuality(70)
        );
    }
}

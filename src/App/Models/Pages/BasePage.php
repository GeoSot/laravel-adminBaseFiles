<?php


namespace GeoSot\BaseAdmin\App\Models\Pages;


use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use GeoSot\BaseAdmin\App\Models\BaseModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\Media\HasMedia;
use Spatie\Translatable\HasTranslations;

abstract class BasePage extends BaseModel
{

    use Sluggable, SluggableScopeHelpers, HasTranslations, HasMedia;


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return ['slug' => ['source' => ['en.title', 'title']]];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Template to use in order to render the Model
     * @return string
     */
    abstract public function getViewTemplate(): string;


    public static function echo(string $slug)
    {
        $model = static::findBySlug($slug);

        return $model ? $model->render() : '';
    }

    public function render()
    {
        view($this->getViewTemplate(), ['record' => $this])->render();
    }


}

<?php


namespace GeoSot\BaseAdmin\App\Models\Pages;


use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use GeoSot\BaseAdmin\App\Models\BaseModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasTranslations;
use GeoSot\BaseAdmin\App\Traits\Eloquent\Media\HasMedia;
use Illuminate\Contracts\Support\Htmlable;


abstract class BasePage extends BaseModel implements Htmlable
{

    use Sluggable, SluggableScopeHelpers, HasTranslations, HasMedia;


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
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
    abstract protected function getViewTemplate(): string;


    public static function asHtml(string $slug)
    {
        $model = static::findBySlug($slug);

        return $model ? $model->toHtml() : '';
    }

    public function toHtml(): string
    {
      return view($this->getViewTemplate(), ['record' => $this])->render();
    }


}

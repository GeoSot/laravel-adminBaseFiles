<?php

namespace GeoSot\BaseAdmin\App\Models\Media;

use Cviebrock\EloquentSluggable\Sluggable;
use GeoSot\BaseAdmin\App\Models\BaseModel;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasTranslations;
use GeoSot\BaseAdmin\App\Traits\Eloquent\Media\HasMedia;


class MediumGallery extends BaseModel
{
    use Sluggable, HasTranslations, HasMedia;

    protected $table = 'media_galleries';

    public $translatable = [
        'title',
        'notes',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'notes',
        'slug',
        'related_type',
        'related_id',
        'show_details',

        'is_enabled',
        'user_id',
        'modified_by',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'show_details' => 'array',
    ];

    /*
    protected $frontEndConfigValues = [
        'admin' => [
            'langDir' => '_MODEL_LANG_DIR_',
            'viewDir' => '_MODEL_VIEWS_DIR_',
            'route'   => '_BASE_ROUTE_',
        ],
    ];*/


    /* protected $errorMessages = [ //Can use ":attribute"
               'title' => ['required' => 'The title field is mandatory.']
       ];
    */


    protected function rules(array $merge = [])
    {
        return array_merge([
            'title' => "required|min:3",
//            'slug' => "min:3|unique:{$this->getTable()},slug".$this->getIgnoreTextOnUpdate(),
        ], $merge, $this->rules);
    }


    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return ['slug' => ['source' => 'title']];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /*
    *
    * - - -  R E L A T I O N S H I P S  - - -
    *
    */

    public function ownerModel()
    {
        //morphedByMany
        return $this->morphTo('related');
    }


    //*********  M E T H O D S  ***************
}

<?php

namespace GeoSot\BaseAdmin\App\Models;

use App\Models\Media\Medium;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use GeoSot\BaseAdmin\App\Traits\Eloquent\Media\HasMedia;
use GeoSot\BaseAdmin\Facades\Settings;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

/**
 * GeoSot\BaseAdmin\App\Models\Setting.
 */
class Setting extends BaseModel
{
    use Sluggable;
    use HasMedia;

    public $choices = [
        'string',
        'boolean',
        'textarea',
        'number',
        'timeToMinutes',
        'dateTime',
        'collectionSting',
        'collectionNumber',
        Medium::class,
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'key',
        'sub_group',
        'group',
        'value',
        'notes',
        'model_type',
        'model_id',
        'type',
        'enabled',
        'modified_by',
    ];

    protected $appends = [
        'value_parsed',
        'value_parsed_to_human',
        'type_to_human',
    ];
    protected $frontEndConfigValues = [
        'admin' => [
            'langDir' => 'settings/setting',
        ],
    ];

    protected $errorMessages = [
        'key'       => ['unique' => 'keySubGroupGroupUnique'],
        'sub_group' => ['unique' => 'keySubGroupGroupUnique'],
        'group'     => ['unique' => 'keySubGroupGroupUnique'],
    ];

    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            foreach (['key', 'sub_group', 'group'] as $name) {
                $model[$name] = lcfirst($model[$name]);
            }

            if (is_array($model->value)) {
                $model->value = json_encode($model->value);
            }
        });
        static::saved(function ($model) {
            Settings::flushKey($model->slug);
            Settings::flushKey('group.'.$model->group);
        });
    }

    public static function getGrouped()
    {
        $settings = parent::orderBy('key', 'ASC')->get();
        $grouped = $settings->groupBy([
            'group',
            'sub_group',
            //            function ($item, $key) {
            //                return (empty($item->group) or is_null($item->group)) ? 'Uncategorised':$item->group;
            //            },
            //            function ($item, $key) {
            //                return (empty($item->sub_group) or is_null($item->sub_group)) ? 'Uncategorised':$item->sub_group;
            //            }
        ])->sortKeys()->map(function ($group, $key) {
            return $group->sortKeys()->map(function ($item1, $key) {
                return $item1->keyBy('key')->sortKeys();
            });
        });

        return $grouped;
    }

    protected function rules(array $merge = [])
    {
        $textOnUpdate = is_null($this->id) ? '' : ','.$this->id;

        $uniqueCombinationRule = Rule::unique($this->getTable())->where(function (Builder $query) {
            return $query->where('key', request()->input('key'))
                ->where('sub_group', request()->input('sub_group'))
                ->where('group', request()->input('group'))
                ->where('model_type', request()->input('model_type'))
                ->where('model_id', request()->input('model_id'))
                ->get();
        })->ignore(($this->id ?? ''));

        return array_merge([
            // 'key'  => "required|min:3|unique:{$this->getTable()},key" . $textOnUpdate,
            'key'       => ['required', 'min:3', $uniqueCombinationRule],
            'sub_group' => ['required_with:group', $uniqueCombinationRule],
            'group'     => [$uniqueCombinationRule],
            'type'      => 'required',
            'slug'      => "min:3|unique:{$this->getTable()},slug".$textOnUpdate,
        ], $merge, $this->rules);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source'    => ['group', 'sub_group', 'slug_key'],
                'separator' => '.',
                'onUpdate'  => true,
                'method'    => function ($string, $sep) {
                    return preg_replace('/[^a-zA-Z0-9-]+/i', $sep, trim($string));
                },
            ],
        ];
    }

    /*
    *
    * - - -  R E L A T I O N S H I P S  - - -
    *
    */

    public function getSlugKeyAttribute()
    {
        if (is_null($this->model_type)) {
            return $this->key;
        }

        return $this->key.'-'.lcfirst(class_basename($this->model_type)).'-'.$this->model_id;
    }

    //*********  M E T H O D S  ***************

    public function ownerModel()
    {
        return $this->morphTo('model');
    }

    public function getValueParsedToHumanAttribute()
    {
        return implode(', ', (array) $this->getValueParsedAttribute());
    }

    public function getValueParsedAttribute()
    {
        $value = Arr::get($this->attributes, 'value');
        if (!$value or empty($value)) {
            return null;
        }

        if (in_array($this->type, ['number', 'timeToMinutes'])) {
            return (float) $value;
        }
        if (in_array($this->type, ['collectionSting', 'collectionNumber'])) {
            return array_map(function ($it) {
                return ($this->type == 'collectionNumber') ? (float) $it : $it;
            }, (array) json_decode($value));
        }
        if ($this->type == 'dateTime') {
            return Carbon::parse($value);
        }
        if ($this->type == 'boolean') {
            return (bool) $value;
        }
        if ($this->type == Medium::class) {
            /* @var Medium $FQN */
            $FQN = $this->type;

            return $FQN::find($value);
        }

        return $value;
    }

    public function getTypeToHumanAttribute()
    {
        return Arr::get(static::getSettingTypes(), $this->type, $this->type);
    }

    /**
     * @return array
     */
    public static function getSettingTypes()
    {
        return [
            'string'           => 'String',
            'textarea'         => 'Textarea',
            'boolean'          => 'Boolean',
            'number'           => 'Number',
            'timeToMinutes'    => 'Time To Minutes',
            'dateTime'         => 'DateTime',
            'collectionSting'  => 'Collection of Strings',
            'collectionNumber' => 'Collection of Numbers',
            Medium::class      => 'Media',
            Medium::TYPE_IMAGE => 'Image',
        ];
    }
}

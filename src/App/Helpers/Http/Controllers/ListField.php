<?php


namespace GeoSot\BaseAdmin\App\Helpers\Http\Controllers;


use Carbon\Carbon;
use GeoSot\BaseAdmin\ServiceProvider;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ListField
{


    /**
     * @var callable|null
     */
    protected $callback;
    private string $property;

    public function __construct(string $property, callable $callback = null)
    {
        $this->property = $property;
        $this->callback = $callback;
    }


    public static function make($arg): self
    {
        if (is_string($arg)) {
            return static::new($arg);
        }
        if ($arg instanceof ListField) {
            return $arg;
        }
        throw new \Exception("ListField couldn't be parsed");
    }

    public static function new(string $property, callable $callback = null): self
    {
        return new self($property, $callback);
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    /**
     * @param  Model  $model
     * @param  Collection  $viewVals
     * @return string|Factory|View|null
     */
    public function parseValue(Model $model, Collection $viewVals)
    {
        if (is_callable($this->callback)) {
            return call_user_func($this->callback, $model, $viewVals);
        }
        return $this->tryToParseGivenProperty($model, $viewVals);
    }

    private static function getSnippetsDir(string $arg): string
    {
        return ServiceProvider::getPackageVariables()->get('blades').'admin._includes.listDataParsingSnippets.'.$arg;
    }

    /**
     * @param  Model  $record
     * @param  Collection  $viewVals
     * @return string|Factory|View|null
     */
    private function tryToParseGivenProperty(Model $record, Collection $viewVals)
    {
        $fieldData = data_get($record, $this->getProperty());
        $array = explode('.', $this->getProperty());
        $dataCollection = count($array) > 1 ? data_get($record, Str::replaceLast('.'.Arr::last($array), '', $this->getProperty())) : null;
        $listName = $this->getProperty();

        if (in_array($listName, ['is_enabled', 'color', 'bg_color'])) {
            return view()->exists(static::getSnippetsDir($listName)) ? view(static::getSnippetsDir($listName), get_defined_vars()) : '';
        }
        if ($viewVals->get('extraValues')->has('linkable') && in_array($listName, $viewVals->get('extraValues')->get('linkable'))) {
            $editRoute = route($viewVals->get('baseRoute').'.edit', $record);
            $text = Str::limit(data_get($record, $listName), 50) ?: '----';
            return '<a href="'.$editRoute.'">'.$text.'</a>';
        }

        if ($fieldData instanceof Carbon) {
            if (is_subclass_of($dataCollection, Model::class)) {
                return data_get($dataCollection->toArray(), Arr::last($array));
            }
            return is_subclass_of($record, Model::class) ? data_get($record->toArray(), $listName) : $fieldData->format('d/m/Y');
        }

        if (is_numeric($fieldData)) {
            return '<div class="text-right">'.$fieldData.'</div>';
        }

        if (is_bool($fieldData)) {
            return view(static::getSnippetsDir('bool'), get_defined_vars());
        }

        if (is_object($dataCollection) && is_subclass_of($dataCollection, Model::class)) {
            return $dataCollection->frontConfigs->getAdminLink(Arr::last($array), false, ['target' => '_blank', 'class' => 'text-muted']);
        }

        if ($dataCollection instanceof \Illuminate\Support\Collection) {
            $result = '';
            foreach ($dataCollection as $dt) {
                $class = 'badge badge-primary badge-pill';
                $style = ($dt->bg_color ?? null) ? "background-color: {$dt->bg_color};" : '';
                if (is_object($dataCollection) && is_subclass_of($dt, Model::class) && $dt->allowedToHandle()) {
                    $result .= $dt->frontConfigs->getAdminLink(Arr::last($array), false, ['class' => $class, 'style' => $style]);
                }
                $result .= '<span class="'.$class.'">'.data_get($dt, $array[1]).'</span>';
            }
            return $result;
        }

        if (filter_var($fieldData, FILTER_VALIDATE_URL)) {
            return '<a target="_blank" href="'.$fieldData.'">'.Str::limit($fieldData, 50).'</a>';
        }
        return ($fieldData === strip_tags($fieldData)) ? Str::limit($fieldData, 50) : $fieldData;
    }
}

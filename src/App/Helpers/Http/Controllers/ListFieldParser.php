<?php


namespace GeoSot\BaseAdmin\App\Helpers\Http\Controllers;


use Carbon\Carbon;
use GeoSot\BaseAdmin\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ListFieldParser
{


    protected Model $record;
    protected Collection $viewVals;
    protected string $property;

    public function __construct(string $property, Model $record, Collection $viewVals)
    {
        $this->record = $record;
        $this->viewVals = $viewVals;
        $this->property = $property;
    }


    public function getValue(callable $callback = null): ?string
    {
        if (is_callable($callback)) {
            return $this->maybeAddLink(call_user_func($callback, $this->record, $this->viewVals));
        }
        return $this->maybeAddLink($this->tryToParseGivenProperty());
    }

    private static function getSnippetsDir(string $arg): string
    {
        return ServiceProvider::getPackageVariables()->get('blades').'admin._includes.listDataParsingSnippets.'.$arg;
    }


    /**
     * @return string
     */
    private function tryToParseGivenProperty()
    {
        $record = $this->record;
        $viewVals = $this->viewVals;
        $fieldData = data_get($record, $this->property);
        $array = explode('.', $this->property);
        $dataCollection = count($array) > 1 ? data_get($record, Str::replaceLast('.'.Arr::last($array), '', $this->property)) : null;
        $listName = $this->property;

        if (in_array($listName, ['is_enabled', 'color', 'bg_color'])) {
            return view()->exists(static::getSnippetsDir($listName)) ? view(static::getSnippetsDir($listName), get_defined_vars())->render() : '';
        }

        if ($fieldData instanceof Carbon) {
            if (is_subclass_of($dataCollection, Model::class)) {
                return data_get($dataCollection->toArray(), Arr::last($array));
            }

            return is_subclass_of($record, Model::class) ?  $record->getAttributeValue($listName) : $fieldData->format('d/m/Y');
        }

        if (is_numeric($fieldData)) {
            return '<div class="text-right">'.$fieldData.'</div>';
        }

        if (is_bool($fieldData)) {
            return view(static::getSnippetsDir('bool'), get_defined_vars())->render();
        }

        if (is_object($dataCollection) && is_subclass_of($dataCollection, Model::class)) {
            return $dataCollection->frontConfigs->getAdminLink(Arr::last($array), false, ['target' => '_blank', 'class' => 'text-muted']);
        }

        if ($dataCollection instanceof \Illuminate\Support\Collection) {
            $result = '';
            foreach ($dataCollection as $dt) {
                $class = 'badge badge-primary badge-pill mx-1';
                $style = ($dt->bg_color ?? null) ? "background-color: {$dt->bg_color};" : '';
                if (is_subclass_of($dt, Model::class) && $dt->allowedToHandle()) {
                    $result .= $dt->frontConfigs->getAdminLink(Arr::last($array), false, ['class' => $class, 'style' => $style]);
                    continue;
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

    private function maybeAddLink(string $recordValue = null):? string
    {
        if ($this->viewVals->get('extraValues')->has('linkable') && in_array($this->property, $this->viewVals->get('extraValues')->get('linkable'))) {
            $editRoute = route($this->viewVals->get('baseRoute').'.edit', $this->record);
            $text = $recordValue ?: '----';
            return '<a href="'.$editRoute.'">'.$text.'</a>';
        }
        return $recordValue;
    }
}

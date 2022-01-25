@php
    use GeoSot\BaseAdmin\App\Models\BaseModel;
    use Illuminate\Support\Collection;
     /**
     * @var Collection $packageVariables
     * @var string $listName
     * @var BaseModel $record
     */
     $snippetsDir=$packageVariables->get('blades').'admin._includes.listDataParsingSnippets.'
@endphp

@switch($parse)
    @case('firstCheckBox')
    @includeIf($snippetsDir.'firstCheckBox')
    @break
    @case('is_enabled')
    @includeIf($snippetsDir.'is_enabled')
    @break
    @case('counter')
    @includeIf($snippetsDir.'counter')
    @break
    @case('boolean')
    @includeIf($snippetsDir.'boolean')
    @break
    @case('fields')


    <td>
        {!!  \GeoSot\BaseAdmin\App\Helpers\Http\Controllers\ListField::make($listName)->parseValue($record, $viewVals) !!}
    </td>
    @break

@endswitch

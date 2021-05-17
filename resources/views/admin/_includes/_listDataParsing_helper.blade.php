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

    @php
        $snippetsDir=$packageVariables->get('blades').'admin._includes.listDataParsingSnippets.';

         $fieldData=data_get($record,$listName);
         $recordIs_Model= is_subclass_of($record, Model::class);
         $fieldIs_Carbon=($fieldData  instanceof Carbon);
         $fieldIs_Collection=($fieldData  instanceof  Collection);
         $fieldIs_Model=is_subclass_of($fieldData, Model::class);
         $array=explode('.',$listName);
         $dataCollection=count($array)>1? data_get($record,Str::replaceLast('.'.Arr::last($array),'',$listName)):null;

    @endphp
    <td>
        @if( in_array($listName,['is_enabled','color','bg_color']) )
            @includeIf($snippetsDir.$listName)
        @elseif($viewVals->get('extraValues')->has('linkable') and in_array($listName,$viewVals->get('extraValues')->get('linkable') ))
            <a href="{{route($viewVals->get('baseRoute').'.edit',$record)}}">{!! Str::limit(data_get($record,$listName),50) ?:'----'!!}</a>
        @elseif($fieldIs_Carbon)
            @if(is_subclass_of($dataCollection, \Illuminate\Database\Eloquent\Model::class))
                {!!data_get($dataCollection->toArray(),Arr::last($array))!!}
            @else
                {!!( $recordIs_Model )?data_get($record->toArray(),$listName):$fieldData->format('d/m/Y') !!}
            @endif
        @elseif(is_numeric($fieldData))
            <div class="text-right">{!! $fieldData !!}</div>
        @elseif(is_bool($fieldData))
            @includeFirst([$snippetsDir.'boolean',$snippetsDir.'bool'])
        @elseif( is_object($dataCollection) and is_subclass_of($dataCollection, \Illuminate\Database\Eloquent\Model::class))
            {!!$dataCollection->frontConfigs->getAdminLink(Arr::last($array), false,['target'=>'_blank','class'=>'text-muted']) !!}
        @elseif( $dataCollection instanceof  \Illuminate\Support\Collection)
            @foreach($dataCollection as $dt)
                @php($class = 'badge badge-primary badge-pill')
                @php($style= ($dt->bg_color??null)?"background-color: {$dt->bg_color};":'')
                @if( is_object($dataCollection) and is_subclass_of($dt, \Illuminate\Database\Eloquent\Model::class) and $dt->allowedToHandle())
                    {!!$dt->frontConfigs->getAdminLink(Arr::last($array), false,['class'=>$class , 'style'=> $style]) !!}
                @else
                    <span class="{!! $class !!}">
                        {!! data_get($dt,$array[1]) !!}
                    </span>
                @endif
            @endforeach
        @elseif( filter_var($fieldData, FILTER_VALIDATE_URL))
            <a target="_blank" href="{{$fieldData}}">{!! Str::limit($fieldData, 50) !!}</a>
        @else
            {!!  ($fieldData === strip_tags($fieldData))?Str::limit($fieldData, 50):$fieldData !!}
        @endif
    </td>
    @break

@endswitch

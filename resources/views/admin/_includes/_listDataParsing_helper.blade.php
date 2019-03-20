@php
    $snippetsDir=$packageVariables->get('blades').'admin._includes.listDataParsingSnippets.';
@endphp

@switch($parse)
    @case('firstCheckBox')
    @includeIf($snippetsDir.'firstCheckBox')
    @break
    @case('enabled')
    @includeIf($snippetsDir.'enabled')
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
        $recordIs_Model= is_subclass_of($record, \Illuminate\Database\Eloquent\Model::class);
        $fieldIs_Carbon=($fieldData  instanceof \Carbon\Carbon);
        $fieldIs_Collection=($fieldData  instanceof  \Illuminate\Support\Collection);
        $fieldIs_Model=is_subclass_of($fieldData, \Illuminate\Database\Eloquent\Model::class);
        $array=explode('.',$listName);
        $dataCollection=data_get($record,$array[0]);


    @endphp
    <td>
        @if( in_array($listName,['enabled','color']) )
            @includeIf($snippetsDir.$listName)
        @elseif($viewVals->get('extraValues')->has('linkable') and in_array($listName,$viewVals->get('extraValues')->get('linkable') ))
            <a href="{{route($viewVals->get('baseRoute').'.edit',$record)}}">{{\Illuminate\Support\Str::limit(data_get($record,$listName),50)}}</a>
        @elseif($fieldIs_Carbon)
            @if(is_subclass_of($dataCollection, \Illuminate\Database\Eloquent\Model::class))
                {!!data_get($dataCollection->toArray(),$array[1])!!}
            @else
                {!!( $recordIs_Model )?data_get($record->toArray(),$listName):$fieldData->format('d/m/Y') !!}
            @endif
        @elseif(is_numeric($fieldData))
            <div class="text-right">{!! $fieldData !!}</div>
        @elseif(is_bool($fieldData))
            @includeFirst([$snippetsDir.'boolean',$snippetsDir.'bool'])
        @elseif(is_subclass_of($dataCollection, \Illuminate\Database\Eloquent\Model::class))
            {!!$dataCollection->getDashBoardLink($array[1], false,['target'=>'_blank']) !!}
        @elseif( $dataCollection instanceof  \Illuminate\Support\Collection)
            @foreach($dataCollection as $dt)
                @if(is_subclass_of($dt, \Illuminate\Database\Eloquent\Model::class))
                    {!!$dt->getDashBoardLink($array[1], false,['class'=>'badge badge-primary badge-pill ']) !!}
                @else
                    <span class="badge badge-pill badge-primary ">
                        {!! data_get($dt,$array[1]) !!}
                    </span>
                @endif
            @endforeach
        @elseif( filter_var($fieldData, FILTER_VALIDATE_URL))
            <a target="_blank" href="{{$fieldData}}">{!! \Illuminate\Support\Str::limit($fieldData, 50) !!}</a>
        @else
            {!!  \Illuminate\Support\Str::limit($fieldData, 50); !!}
        @endif
    </td>
    @break

@endswitch

@extends($packageVariables->get('adminLayout'))
@php
    use Illuminate\Support\Collection;
     /**
     * @var Collection $packageVariables
     * @var Collection $viewVals
     */
@endphp

@component($packageVariables->get('blades').'admin._components.createEditButtons',['viewVals'=>$viewVals]    )
@endcomponent
@section('content')
    @php($form=$viewVals->get('extraValues')->get('form'))
    {!!  form_start($form) !!}

    <div class="row">
        @includeWhen($viewVals->get('action') == 'create',$packageVariables->get('blades').'admin.settings._create')
        @includeWhen($viewVals->get('action') == 'edit',$packageVariables->get('blades').'admin.settings._edit')

        @foreach($viewVals->get('extraValues')->get('settingsExistingValues') as $listName=>$list)
            <datalist id="{{$listName}}">
                @foreach($list as $option)
                    <option value="{{$option}}">
                @endforeach
            </datalist>
        @endforeach

    </div>
    {!!form_end($form, true); !!}
@endsection

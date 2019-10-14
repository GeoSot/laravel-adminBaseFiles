@extends($packageVariables->get('blades').'admin.mediaModels.mediaModelForm')

@section('mediaModelExtraContent')
    @component($packageVariables->get('blades').'admin._components.formCard' )
        @if($viewVals->get('record'))
            @lang("{$viewVals->get('modelLang')}.fields.fileLink")
            {!! $viewVals->get('record')->getAsLink('btn-block'); !!}
        @endif
    @endcomponent
@endSection

@extends($packageVariables->get('blades').'admin.Media.mediaModelForm')

@section('mediaModelExtraContent')
    @component($packageVariables->get('blades').'admin._components.formCard' )
        {{''/* @var \App\Models\Media\MediumImage $record */}}
        @php($record=$viewVals->get('record'))
        @if($record)
            @lang("{$viewVals->get('modelLang')}.fields.fileLink")
            {!!$record->getAsLink('btn-block'); !!}
        @endif
    @endcomponent
@endSection

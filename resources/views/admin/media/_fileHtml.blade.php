@component($packageVariables->get('blades').'admin._components.formCard' )
    {{''/* @var \App\Models\Media\Medium $record */}}
    @php($record=$viewVals->get('record'))
    @if($record)
        @lang("{$viewVals->get('modelLang')}.fields.fileLink")
        {!!$record->getLinkHtml('btn-block'); !!}
    @endif
@endcomponent


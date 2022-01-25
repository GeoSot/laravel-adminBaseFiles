@component($packageVariables->get('blades').'admin._components.formCard' )
    {!! '' /* @var \App\Models\Media\Medium $record */ !!}
    @php($record=$viewVals->get('record'))

    {!! $record->getLinkHtml('btn-block'); !!}
    <div class=" my-4 "> {!! $record->getHtml(); !!}</div>

    @if($thumb=$record->findVariant(\App\Models\Media\Medium::VARIANT_NAME_THUMB))
        <div class="border-top my-4 pt-2"> @lang("{$viewVals->get('modelLang')}.fields.thumb")</div>
        <div>{!!$thumb->getHtml() !!}</div>
    @endif

@endcomponent


@extends($packageVariables->get('blades').'admin.Media.mediaModelForm')

@section('mediaModelExtraContent')
    @component($packageVariables->get('blades').'admin._components.formCard' )
        {!! '' /* @var \App\Models\Media\MediumImage $record */ !!}
        @php($record=$viewVals->get('record'))

        {!! $record->getAsLink('btn-block'); !!}
        <div class=" my-4 "> {!! $record->getImgHtml(); !!}</div>

        @if(!$record->thumb)
            <div class="border-top my-4 pt-2"> @lang("{$viewVals->get('modelLang')}.fields.thumb")</div>
            <div>{!!optional($record)->getThumb() !!}</div>
        @endif

    @endcomponent
@endSection

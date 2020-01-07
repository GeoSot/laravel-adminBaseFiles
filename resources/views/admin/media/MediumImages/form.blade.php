@extends($packageVariables->get('blades').'admin.Media.mediaModelForm')

@section('mediaModelExtraContent')
    @component($packageVariables->get('blades').'admin._components.formCard' )
        @if(!$viewVals->get('record'))
            @lang("{$viewVals->get('modelLang')}.fields.fileLink")
            {!! $viewVals->get('record')->getAsLink('btn-block'); !!}
        @endif
        <div class=" my-4 "> {!! $viewVals->get('record')->getImgHtml(); !!}</div>

        @if(!$viewVals->get('record')->thumb)
            <div class="border-top my-4 pt-2"> @lang("{$viewVals->get('modelLang')}.fields.thumb")</div>
            <div>{!!optional( $viewVals->get('record'))->getThumb() !!}</div>
        @endif

    @endcomponent
@endSection

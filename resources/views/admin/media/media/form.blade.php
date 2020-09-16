@extends($packageVariables->get('adminLayout'))
@php
    use Illuminate\Support\Collection;
     /**
     * @var Collection $packageVariables
     * @var Collection $viewVals
      * @var \Kris\LaravelFormBuilder\Form $form
     */
@endphp
@component($packageVariables->get('blades').'admin._components.createEditButtons',['viewVals'=>$viewVals]    )   @endcomponent

@section('content')
    @php
        $form=$viewVals->get('extraValues')->get('form');
        $record=$viewVals->get('record');


    @endphp


    <div class="row">
        <div class="col-xl-5 col-md-6 col-12 mb-3 order-0 order-xl-0">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.first')] )
                {!! form($form) !!}
            @endcomponent
        </div>



        @switch($record->aggregate_type)
            @case(\GeoSot\BaseAdmin\App\Models\Media\Medium::TYPE_IMAGE)
            @case(\GeoSot\BaseAdmin\App\Models\Media\Medium::TYPE_IMAGE_VECTOR)
            @include($packageVariables->get('blades').'admin.media._imageHtml')
            @break

            @default
            @include($packageVariables->get('blades').'admin.media._fileHtml')
        @endswitch


        <div class="col-xl-4 col-12 mb-3  order-3 order-xl-2">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.second')] )
                @php( $staticValues=['disk','directory','filename','thumb','aggregate_type','mime_type','extension'])
                @foreach($staticValues as $key)
                    <div class="mb-3">
                        <span class="text-muted">@lang("{$viewVals->get('modelLang')}.fields.$key"):</span>
                        {{data_get($record,$key)}}
                    </div>
                @endforeach
                <div class="mb-3">
                    <span class="text-muted">@lang("{$viewVals->get('modelLang')}.fields.size"):</span>
                    {{$record->readableSize()}}
                </div>
                @if($record && $record->custom_properties)
                    <div class="mt-4 small">
                        <span class="text-muted">@lang("{$viewVals->get('modelLang')}.fields.custom_properties"):</span>
                        @json($record->custom_properties)
                    </div>
                @endif
{{--                @if($record && $record->models())--}}
{{--                    <div class="pt-3 mb-4 border-top"><span class="font-weight-bold text-muted ">@lang("{$viewVals->get('modelLang')}.fields.ownerModel.title") :</span>--}}
{{--                        {!! $record->ownerModel->getDashboardLink(class_basename($record->ownerModel)." (ID: {$record->ownerModel->getKey()})") !!}--}}
{{--                    </div>--}}
{{--                @endif--}}



            @endcomponent
        </div>



    </div>


@endsection



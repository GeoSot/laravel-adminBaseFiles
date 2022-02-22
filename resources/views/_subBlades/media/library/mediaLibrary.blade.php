@php
    $acceptTypes=$accept?? null;
    $acceptTypes= ($acceptTypes)===\App\Models\Media\Medium::TYPE_ALL?null:$acceptTypes;
@endphp
<media-library
    media-route="{{route('admin.media.index')}}"
    input-name="{{$inputName}}"
    v-bind:multiple="@json($multiple??false)"
    v-bind:is-library="@json($library??true)"
    v-bind:is-grouped="@json($grouped??true)"
    v-bind:accepted-types="{{\Illuminate\Support\Js::from($acceptTypes)}}"
    v-bind:uppy-options="{{\Illuminate\Support\Js::from(\GeoSot\BaseAdmin\Helpers\Uploads::getUppyOptions($acceptTypes?:'*'))}}">
</media-library>


<media-library
    media-route="{{route('admin.media.index')}}"
    input-name="{{$inputName}}"
    v-bind:multiple="@json($multiple??false)"
    v-bind:is-library="@json($library??true)"
    v-bind:is-grouped="@json($grouped??true)"
    v-bind:uppy-options="{{\Illuminate\Support\Js::from(\GeoSot\BaseAdmin\Helpers\Uploads::getUppyOptions($accept?? ['*/*']))}}">
</media-library>


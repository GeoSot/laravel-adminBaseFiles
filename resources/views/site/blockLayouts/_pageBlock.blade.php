@php
    /**
     * @var \GeoSot\BaseAdmin\App\Models\Pages\PageBlock $record
     */
@endphp
<div data-slug="{{$record->slug}}" class="{{$record->css_class}}" @if($record->background_color) style="background-color: {{$record->background_color}}" @endif>
    @includeFirst([$record->layout,'baseAdmin::site.blockLayouts.simple.default'])
</div>

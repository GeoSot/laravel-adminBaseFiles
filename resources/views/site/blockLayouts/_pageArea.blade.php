@php
    /**
     * @var \GeoSot\BaseAdmin\App\Models\Pages\PageArea $record
     */

@endphp
<section id="pageArea_{{$record->id}}" data-slug="{{$record->slug}}" class="{{$record->css_class}}"
         @if($record->background_color ||$record->background_image)
         style="
         @if($record->background_color )
             background-color: {{$record->background_color}};
         @endif
         @if($record->background_image )
             background-image:url({!! optional($record->background_image)->getUrl()!!});
         @endif
             " @endif
>


    <h4 class="lead"> {!! $record->title!!}</h4>
    <h5 class=""> {!! $record->sub_title!!}</h5>

    @each('baseAdmin::site.blockLayouts._pageBlock', $record->blocks, 'record')

</section>

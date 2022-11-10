@php
     /**
     * @var \GeoSot\BaseAdmin\App\Models\Setting $record
     */
@endphp

<section id="set-{{$record->slug}}" class="row"
         @if($record->notes)data-toggle="popover" data-content="{{$record->notes}}" data-trigger="hover" data-html="true" data-placement="bottom" data-container="body"  @endif
>
    <a href="{{route('admin.settings.edit', $record)}}" title="Edit Value" class="col-4" target="_blank">
        {{$record->slug}}
    </a>
    <div class="col-8">{{$record->value}}</div>
</section>

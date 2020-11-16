@php
    /**
     * @var \GeoSot\BaseAdmin\App\Models\Pages\PageBlock $record
     */
@endphp
<h2 class=""> {!! $record->title!!}</h2>
<h5 class=""> {!! $record->sub_title!!}</h5>
<h5 class=""> {!! $record->notes!!}</h5>

@foreach($record->images()->get() as $image )
    {!! $image->getHtml() !!}
@endforeach

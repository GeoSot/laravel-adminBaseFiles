@extends($packageVariables->get('siteLayout'))

@php
    /**
     * @var \App\Models\Pages\Page $record
    */
@endphp
@section('documentTitle'){!! $record->title !!}@endsection


@section('content')

    <div data-slug="page_{{$record->slug}}" class="">
        <h2 class="lead"> {!! $record->sub_title!!}</h2>

        @each('baseAdmin::site.blockLayouts._pageArea', $record->pageAreas, 'record')

    </div>
@endsection

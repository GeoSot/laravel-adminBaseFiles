@extends($packageVariables->get('siteLayout'))

@php
    /**
     * @var \App\Models\Pages\Page $page
    */
@endphp
@section('documentTitle'){!! $page->title !!}@endsection


@section('content')

    <div data-slug="page_{{$page->slug}}" class="">
        <h2 class="lead"> {!! $page->sub_title!!}</h2>

        @each('baseAdmin::site.blockLayouts._pageArea', $page->pageAreas, 'record')

    </div>
@endsection

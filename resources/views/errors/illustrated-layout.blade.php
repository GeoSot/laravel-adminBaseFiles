@extends($packageVariables->get('siteLayout'))


@section('content')
    <div data-page="errorPage  " class="row h-100 justify-content-around position-relative">

            <div class=" col-12 col-md-6  d-flex flex-column justify-content-around px-3 px-sm-5 py-4 messageWrapper">
                    <h1 class="header ">
                        {!! $__env->yieldContent('title', __($packageVariables->get('nameSpace')."site/generic.errorPages.codeTitles.{$exception->getStatusCode()}")) !!}
                    </h1>
                <div class="my-5 font-italic h1 fa-5x text-muted px-4 ">
                    {{ $exception->getStatusCode() }}
                </div>
                <div class="content h3">
                    {!!  $__env->yieldContent('message',__($packageVariables->get('nameSpace')."site/generic.errorPages.codeMessages.{$exception->getStatusCode()}")) !!}
                </div>
                <div class="content">
                    {{ $exception->getMessage() }}
                </div>
                <div class="buttons text-right ">
                    <a class="btn btn-outline-dark btn-lg" href="{{ \App\Providers\RouteServiceProvider::HOME }}">
                        @lang($packageVariables->get('nameSpace').'site/generic.errorPages.returnHomeBtn')
                    </a>
                </div>
            </div>
        @php
            $imgSection=    $__env->yieldContent('image',null);
            preg_match_all('/background-image.*?url\((.*?)\)/mi', $imgSection, $matches);
            $imgUrl=\Illuminate\Support\Arr::get(\Illuminate\Support\Arr::get($matches,1,[]),0)
        @endphp

        <div class="col-12 col-md-6 imgWrapper h-100 "
             style="background-image: @if (!empty($imgUrl))  url('{{ $imgUrl}}') @else linear-gradient(141deg, #9fb8ad 0%, #1fc8db 51%, #2cb5e8 75%) @endif " >
        </div>

    </div>
@endsection


@push('styles')
    <style data-comment="error-page css">
        #mainContent-wrapper {
            width: 100%;
            max-width: 100%;
            height: 91vh !important;
        }

        #app {
            margin-top: 0 !important;
        }
        .imgWrapper{
            background-size: cover;
            background-repeat: no-repeat;
        }

        @media only screen and (max-width: 768px) {
            .messageWrapper{
                z-index: 1;
            }
            .imgWrapper {
                position: absolute;
                opacity: .6;
            }
        }
    </style>
@endpush

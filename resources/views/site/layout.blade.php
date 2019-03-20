<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{str_replace('_', '-', app()->getLocale())}}" xml:lang="{{config('app.locale')}}" itemscope itemtype="http://schema.org/WebSite">
    <head>
        @includeIf('site._includes.headMetas')
{{--        <link href="{{mix("css/site/app.css")}}" rel="stylesheet"/>--}}
        <link href="{{baseAdmin_asset("css/site/app.css")}}" rel="stylesheet"/>
        @stack('styles')
        @include($packageVariables->get('blades').'_subBlades.wysiwyg_Editors.index',['editor'=>'quill'])
    </head>

    <body data-layout="site">
        <div class=" d-flex flex-column justify-content-between min-vh-100">
            @includeIf($packageVariables->get('blades').'site._includes.header')
            <div id="app" class="mainWrapper  d-flex flex-column h-100  mt-4">
                @hasSection ('documentTitle')
                    <div class="page-header   container-fluid">
                        <div class=" ">
                            <h1 class="" itemscope itemprop="mainEntity" itemtype="http://schema.org/Thing">@yield('documentTitle')</h1>
                        </div>
                    </div>
                @endif
                <div id="topBar" class="container">
                    @include($packageVariables->get('blades').'_subBlades.impersonating',['class'=>'alert-primary'])
                    @stack('topBar')
                </div>
                <div class="notificationsContainer container">
                    @include($packageVariables->get('blades').'_subBlades.notifications')
                </div>
                <main id="mainContent-wrapper" class="{{$containerClass??'container'}}  pt-3 d-flex flex-fill flex-column h-100" role="main" itemprop="mainContentOfPage" itemscope="itemscope"
                      itemtype="http://schema.org/WebPageElement">
                    @yield('content')
                </main>
            </div>

            <section id="footer" itemscope itemtype="http://schema.org/WPFooter" class="bg-artified  py-5">
                @includeIf($packageVariables->get('blades').'site._includes.footer')
            </section>
        </div>
        <div class="modals-area">
            @stack('modals')
        </div>

        <span class="js-scripts">
            <script src="{{baseAdmin_asset("js/site/app.js")}}"></script>
{{--            <script src="{{mix("js/site/app.js")}}"></script>--}}

            @stack('scripts')
            @stack('scripts-2')
        </span>
    </body>

</html>

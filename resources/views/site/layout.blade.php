<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="{{str_replace('_', '-', app()->getLocale())}}" xml:lang="{{config('app.locale')}}" itemscope itemtype="http://schema.org/WebSite"
      data-layout="site">
<head>
    @include($packageVariables->get('blades').'site._includes.headMetas')
    <link href="{{\GeoSot\BaseAdmin\Helpers\Base::adminAssets("css/app.css")}}" rel="stylesheet"/>
    @foreach(config('baseAdmin.config.site.extraJs') as $file)
        <link href="{{mix($file)}}" rel="stylesheet"/>
    @endforeach
    @stack('styles')
    @include($packageVariables->get('blades').'_subBlades.wysiwyg_Editors.index',['editor'=>'quill'])
</head>

<body data-layout="site">
<div class=" d-flex flex-column justify-content-between min-vh-100">
    @includeIf($packageVariables->get('blades').'site._includes.header')
    <div id="app" class="mainWrapper  d-flex flex-column h-100  mt-4">
        @hasSection('documentTitle')
            <div class="page-header container">
                <h1 class="text-muted" itemscope itemprop="mainEntity" itemtype="http://schema.org/Thing">@yield('documentTitle')</h1>
            </div>
        @endif
        <div id="topBar" class="container">
            @include($packageVariables->get('blades').'_subBlades.impersonating',['class'=>'alert-primary'])
            @stack('topBar')
        </div>
        <div class="notificationsContainer container">
            @include($packageVariables->get('blades').'_subBlades.notifications.index')
        </div>
        <main id="mainContent-wrapper" class="{{$containerClass??'container'}}  pt-3 d-flex flex-fill flex-column h-100" role="main" itemprop="mainContentOfPage"
              itemscope="itemscope"
              itemtype="http://schema.org/WebPageElement">
            @yield('content')
        </main>
    </div>

    <section id="footer" itemscope itemtype="http://schema.org/WPFooter" class="bg-info py-5">
        @includeIf($packageVariables->get('blades').'site._includes.footer')
    </section>
</div>
<div class="fixed-bottom mb-5 mr-2 w-100" aria-live="polite" aria-atomic="true" style="left: auto; max-width: 270px; ">
    @stack('toasts')
</div>

<div class="modals-area">
    @stack('modals')
</div>

<span class="js-scripts">
     <script src="{{\GeoSot\BaseAdmin\Helpers\Base::adminAssets("js/app.js")}}"></script>
    @foreach(config('baseAdmin.config.site.extraJs') as $file)
        <script defer src="{{mix($file)}}"></script>
    @endforeach

    @stack('scripts')
    @stack('scripts2')
</span>
</body>

</html>

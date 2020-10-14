<!DOCTYPE html>
<html lang="{{str_replace('_', '-', app()->getLocale())}}" xml:lang="{{config('app.locale')}}"  data-layout="admin">
<head>

    @include($packageVariables->get('blades').'admin._includes.headMetas')
    <link href="{{\GeoSot\BaseAdmin\Helpers\Base::adminAssets("css/admin.css")}}" rel="stylesheet"/>
    @foreach(config('baseAdmin.config.backEnd.extraCss') as $file)
        <link href="{{mix($file)}}" rel="stylesheet"/>
    @endforeach
    {{--    <link href="{{mix("css/admin/app.css")}}" rel="stylesheet"/>--}}
    @stack('styles')
    @include($packageVariables->get('blades').'_subBlades.wysiwyg_Editors.index',['editor'=>'quill'])
</head>
<body data-layout="admin" class="">
@php($leftSideClasses='col-12 col-sm-4 col-md-3 col-lg-3 col-xl-2')
@includeIf($packageVariables->get('blades').'admin._includes.header')
<div id="app" class="wrapper    offcanvas-wrapper  ">
    <aside id="sideBar" class="overflow-hidden offcanvas-sidebar  navbar px-0  bg-admin navbar-dark align-items-start {{$leftSideClasses}}">
        @include($packageVariables->get('blades').'admin._includes.sidebar.index')
    </aside>

    <div class="offcanvas-main flex-fill d-flex flex-column">
        @include($packageVariables->get('blades').'admin._includes.contentHeader')

        <div id="topBar" class="">
            @include($packageVariables->get('blades').'_subBlades.impersonating',['class'=>'alert-warning'])
            @stack('topBar')
        </div>
        <div class="notificationsContainer container-fluid">
            @include($packageVariables->get('blades').'_subBlades.notifications.index')
            @stack('notifications')
        </div>

        <main id="mainContent-wrapper" class=" container-fluid mt-3 d-flex flex-fill flex-column mb-5">
            @yield('content')
        </main>
    </div>
</div>

<section id="footer">
    @include($packageVariables->get('blades').'_subBlades.returnToTopButton')
    @includeIf($packageVariables->get('blades').'admin._includes.footer')
</section>

<div class="fixed-bottom mb-5 mr-2" aria-live="polite" aria-atomic="true" style="left: auto; max-width: 270px">
    @stack('toasts')
</div>

<div class="modals-area">
    @stack('modals')
</div>


<span class="js-scripts">
    <script src="{{\GeoSot\BaseAdmin\Helpers\Base::adminAssets("js/admin.js")}}"></script>
    @foreach(config('baseAdmin.config.backEnd.extraJs') as $file)
        <script defer src="{{$file}}"></script>
    @endforeach

    @stack('scripts')
    @stack('scripts2')
</span>
</body>

</html>

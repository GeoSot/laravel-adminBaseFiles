<!DOCTYPE html>
<html lang="{{str_replace('_', '-', app()->getLocale())}}" xml:lang="{{config('app.locale')}}">
<head>

    @include($packageVariables->get('blades').'admin._includes.headMetas')
    <link href="{{baseAdmin_asset("css/admin/app.css")}}" rel="stylesheet"/>
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
            @include($packageVariables->get('blades').'_subBlades.notifications')
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
<div class="modals-area">
    @stack('modals')
</div>

<span class="js-scripts">
    <script src="{{baseAdmin_asset("js/admin/app.js")}}"></script>
{{--    <script src="{{mix("js/admin/app.js")}}"></script>--}}
    @stack('scripts')
    @stack('scripts-2')
</span>
</body>

</html>

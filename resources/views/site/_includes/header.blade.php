<header class="js-mainHeader  sticky-top ">
    <nav class="navbar navbar-expand-lg  bg-white navbar-light shadow-sm " style="font-size: 1rem">
        <div class="container-fluid px-0 ">
            <a href="{{ \App\Providers\RouteServiceProvider::HOME }}" class="navbar-brand  px-sm-2 rounded"
               title="@lang($packageVariables->get('nameSpace').'site/app.title')">
                @lang($packageVariables->get('nameSpace').'site/app.title')
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavbarToggler"
                    aria-controls="mainNavbarToggler" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse font-weight-bold ml-auto" id="mainNavbarToggler">
                <ul class="navbar-nav mr-auto  ">
                    @includeIf($packageVariables->get('blades').'site._includes.header-main-menu')
                </ul>
                {{--<div class="nav-item"> --}}{{--@include('admin.layouts._notifications')--}}{{--</div>--}}
                <ul class="navbar-nav  ">
                    @includeIf($packageVariables->get('blades').'site._includes.header-user-dropdown')
                    @includeIf($packageVariables->get('blades').'site._includes.header-login-register')
                    @includeWhen(\GeoSot\BaseAdmin\Helpers\Base::isMultiLingual(),$packageVariables->get('blades'). '_subBlades.languagesDropdown')
                </ul>
            </div>
        </div>
    </nav>
</header>

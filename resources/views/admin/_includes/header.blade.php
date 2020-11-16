<header class="js-mainHeader sticky-top  shadow-sm">
    <nav class="navbar navbar-expand  navbar-dark bg-admin ">
        <div class="container-fluid flex-wrap">
            <div class=" {{$leftSideClasses}} text-center">
                <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
                    @lang($packageVariables->get('nameSpace').'admin/app.title')

                    <small class="small"> @lang($packageVariables->get('nameSpace').'admin/app.version')</small>
                </a>
            </div>

            <div class="navbar-nav  flex-fill ">
                <button class="btn btn-admin border border-secondary rounded-0" type="button" data-toggle="offcanvas" data-target="#sideBar" data-parent="#app"
                        aria-controls="sideBar"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="nav-item ml-auto">
                    {{--@include($packageVariables->get('blades').'admin.layouts._messages')--}}
                </div>
                {{--<div class="nav-item"> --}}{{--@include($packageVariables->get('blades').'admin.layouts._notifications')--}}{{--</div>--}}

                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false">
                        <i class="fas fa-user  pr-1 "></i>
                        {{ auth()->user()->first_name }}
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class=" dropdown-item" href="{{ \App\Providers\RouteServiceProvider::HOME}}">
                            <i class="fas fa-sitemap mr-1 "></i> @lang($packageVariables->get('nameSpace').'admin/generic.menu.site')
                        </a>

                        <a class=" dropdown-item" href="{{route('admin.users.edit',auth()->user())}}">
                            <i class="fas fa-user-cog mr-1 "></i> @lang($packageVariables->get('nameSpace').'admin/generic.menu.user.profile')
                        </a>
                        <a class=" dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-power-off mr-1 "></i> @lang($packageVariables->get('nameSpace').'site/generic.menu.user.logout')
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
                    </div>
                </div>
                @include($packageVariables->get('blades'). '_subBlades.languagesDropdown')
            </div>
        </div>
    </nav>
</header>

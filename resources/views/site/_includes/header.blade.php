<header class="js-mainHeader  sticky-top ">
    <nav class="navbar navbar-expand-lg  bg-white navbar-light shadow-sm " style="font-size: 1rem">
        <div class="container-fluid px-0 ">
            <a href="{{ \App\Providers\RouteServiceProvider::HOME }}" class="navbar-brand  px-sm-2 rounded"
               title="@lang($packageVariables->get('nameSpace').'site/app.title')">
                @lang($packageVariables->get('nameSpace').'site/app.title')
            </a>

            <buitton class="navbar-toggler" type="button" data-toggle="collapse" data-target="#mainNavbarToggler"
                    aria-controls="mainNavbarToggler" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </buitton>

            <div class="collapse navbar-collapse font-weight-bold ml-auto" id="mainNavbarToggler">
                <ul class="navbar-nav mr-auto  ">
                    @includeIf($packageVariables->get('blades').'site._includes.header-main-menu')
                </ul>
                {{--<div class="nav-item"> --}}{{--@include('admin.layouts._notifications')--}}{{--</div>--}}
                <ul class="navbar-nav  ">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false">
                                <i class="fas fa-user  pr-1 "></i>
                                {{ auth()->user()->first_name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuProfile">
                                @if( auth()->user()->isAbleTo('admin.*') )
                                    <a class="dropdown-item" href="{{   route('admin.dashboard')}}">
                                        <i class="fas fa-home mr-1 "></i> @lang($packageVariables->get('nameSpace').'site/generic.menu.dashboard')
                                    </a>
                                @endif
                                <a class=" dropdown-item" href="{{ route('site.users.edit') }}">
                                    <i class="fas fa-user-cog mr-1 "></i> @lang($packageVariables->get('nameSpace').'site/generic.menu.user.profile')
                                </a>
                                <a class=" dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-power-off mr-1 "></i> @lang($packageVariables->get('nameSpace').'site/generic.menu.user.logout')
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">{{ csrf_field() }}</form>
                            </div>
                        </li>
                    @endauth
                    @guest
                        @if (Route::has('register'))
                            <li class="nav-item ">
                                <a class="nav-link" href="{{route('register')}}">{{ __('Register') }}</a>
                            </li>
                        @endif
                        <li class="nav-item ">
                            <a href="{{route('login')}}" class="nav-link ">
                                @lang($packageVariables->get('nameSpace').'site/generic.menu.user.login')
                            </a>
                        </li>
                    @endguest
                    @includeWhen(\GeoSot\BaseAdmin\Helpers\Base::isMultiLingual(),$packageVariables->get('blades'). '_subBlades.languagesDropdown')

                </ul>
            </div>
        </div>
    </nav>
</header>

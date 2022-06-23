@auth
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" data-toggle="dropdown" aria-haspopup="true"
           aria-expanded="false">
            <i class="fas fa-user  pr-1 "></i>
            {{ auth()->user()->first_name }}
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            @if( auth()->user()->isAbleTo('admin.*') )
                <a class="dropdown-item" href="{{   route('admin.dashboard')}}">
                    <i class="fas fa-home mr-1 "></i> @lang($packageVariables->get('nameSpace').'site/generic.menu.dashboard')
                </a>
            @endif
            <a class="dropdown-item" href="{{ route('site.users.edit') }}">
                <i class="fas fa-user-cog mr-1 "></i> @lang($packageVariables->get('nameSpace').'site/generic.menu.user.profile')
            </a>
            @if(\Laravel\Fortify\Features::enabled(\Laravel\Fortify\Features::twoFactorAuthentication()))
                <a class="dropdown-item" href="{{ route('site.users.2fa') }}">
                    <i class="fas fa-user-shield mr-1 "></i> @lang($packageVariables->get('nameSpace').'site/generic.menu.user.2fa')
                </a>
            @endif
            <a class="dropdown-item" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-power-off mr-1 "></i> @lang($packageVariables->get('nameSpace').'site/generic.menu.user.logout')
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                  style="display: none;">{{ csrf_field() }}</form>
        </div>
    </li>
@endauth


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

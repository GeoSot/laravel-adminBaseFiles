<li class="pl-3 nav-item @if(Str::is($subMenu['url'].'*',url()->current())  )) active @endif">
    <a href="{{$subMenu['url']}}" class="nav-link px-3 inner-level-link d-flex align-items-center">
        <i class="fas fa-angle-double-right mr-2"></i>
        <span class="title">{{$subMenu['trans']}}</span>
    </a>
</li>

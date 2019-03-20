@php($subMenuPlural = \Illuminate\Support\Str::plural($subMenu))
@php($routeName =($subMenu == $parentRoute)? "admin.{$subMenuPlural}.index": "admin.{$parentPlural}.{$subMenuPlural}.index")

@php($values=[
'active'=>Request::is("admin/{$parentPlural}/{$subMenuPlural}*") or (Request::is("admin/{$parentPlural}") and  $subMenu == $parentRoute),
'title'=>($subMenu == $parentRoute)?__($packageVariables->get('nameSpace').'admin/generic.menu.listTitle'):__($packageVariables->get('nameSpace').'admin/'.$parentPlural.'/'.$parentRoute.ucfirst($subMenu).'.general.menuTitle'),
'url'=> getCachedRouteAsLink($routeName)
])
<li class="pl-3 nav-item @if($values['active']) active @endif">
    <a href="{{$values['url']}}" class="nav-link px-3 inner-level-link d-flex align-items-center">
        <i class="fa fa-angle-double-right mr-2"></i>
        <span class="title">
            {{$values['title']}}
        </span>
    </a>
</li>

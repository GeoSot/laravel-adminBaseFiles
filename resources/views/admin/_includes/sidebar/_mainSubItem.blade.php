@php    /**
    * @var array $node
    * @var string $parentRoute
    * @var string $subMenu
    */
@endphp
@php($subMenuPlural = Str::plural($subMenu))
@php($routeName =($subMenu == $parentRoute)? "admin.{$subMenuPlural}.index": "admin.{$parentPlural}.{$subMenuPlural}.index")
@php($routePrefix=LaravelLocalization::getCurrentLocale()."/admin/{$parentPlural}")

@php($values=[
'active'=>Request::is($routePrefix."/{$subMenuPlural}*") or (Request::is($routePrefix) and  $subMenu == $parentRoute),
'title'=>($subMenu == $parentRoute)?trans_with_fallback('admin/generic.menu.listTitle'):trans_with_fallback('admin/'.$parentPlural.'/'.$parentRoute.ucfirst($subMenu).'.general.menuTitle'),
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

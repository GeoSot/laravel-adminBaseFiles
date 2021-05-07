@php    /**
    * @var array $node
    * @var string $parentRoute
    * @var string $subMenu
    */
@endphp
@php($subMenuPlural = Str::plural($subMenu))
@php($routeName =($subMenu == $parentRoute)? "admin.{$subMenuPlural}.index": "admin.{$parentPlural}.{$subMenuPlural}.index")
@php($routePrefix=\GeoSot\BaseAdmin\Helpers\Base::isMultiLingual()?LaravelLocalization::getCurrentLocale().'/':''."admin/{$parentPlural}")

@php($values=[
'active'=>Request::is($routePrefix."/{$subMenuPlural}*") or (Request::is($routePrefix) and  $subMenu == $parentRoute),
'title'=>($subMenu == $parentRoute)? \GeoSot\BaseAdmin\Helpers\Base::transWithFallback('admin/generic.menu.listTitle'): \GeoSot\BaseAdmin\Helpers\Base::transWithFallback('admin/'.$parentPlural.'/'.$parentRoute.ucfirst($subMenu).'.general.menuTitle'),
'url'=> \GeoSot\BaseAdmin\Helpers\Base::getCachedRouteAsLink($routeName)
])
<li class="pl-3 nav-item @if($values['active']) active @endif">
    <a href="{{$values['url']}}" class="nav-link px-3 inner-level-link d-flex align-items-center">
        <i class="fas fa-angle-double-right mr-2"></i>
        <span class="title">
            {{$values['title']}}
        </span>
    </a>
</li>

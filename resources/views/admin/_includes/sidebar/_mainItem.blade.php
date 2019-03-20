@php
    $parentPlural = isset($node['plural']) ? $node['plural'] : \Illuminate\Support\Str::plural($parentRoute);
    $hasInnerMenus=\Illuminate\Support\Arr::has($node, 'menus') ;

    //Gather All Permissions In Order To Find if User Can se the Item
    //**************
    $parentPermission="admin.index-{$parentRoute}";
    $isExcludedFromConfFile= in_array( $parentRoute,\Illuminate\Support\Arr::get($node,'excludeFromSideBar',[]));

    $canSeeMenu=(auth()->user()->can($parentPermission) and !$isExcludedFromConfFile );
   if( $hasInnerMenus ){
        $children= array_diff ( $node['menus'] , [$parentRoute]);
        $childPermissions= array_map(function ($child) use ($parentRoute) {
            return 'admin.index-'.$parentRoute.ucfirst($child);
        }, $children);

       $canSeeMenu= auth()->user()->can(array_merge([$parentPermission], $childPermissions));
   }
 //**************


@endphp

@if(config($packageVariables->get('package').'.main.permissionsCheckOnSideBar',true)? $canSeeMenu : !$isExcludedFromConfFile )
    <li class=" nav-item @if($activeParent=Request::is("admin/$parentPlural*")) active  @endif ">
                            <a class="px-3 nav-link  d-flex align-items-center" href="{{ getCachedRouteAsLink( "admin.$parentPlural.index")}}"
                               @if($activeParent and $hasInnerMenus) aria-expanded="true" @endif
                               @if($hasInnerMenus) data-toggle="collapse" data-target="#collapse_{{$parentPlural}}" role="button" aria-expanded="false"
                               aria-controls="collapse_{{$parentPlural}}" @endif>
                                    @isset($node['icon'])
                                    @php($icon = $node['icon'])
                                    <i class=" {{ \Illuminate\Support\Arr::get($icon,'class')}}  mr-2 pt-1 align-self-start"
                                       style=" {{ \Illuminate\Support\Arr::get($icon,'style')}}"></i>
                                @endisset
                                <span class="title "> @lang($packageVariables->get('nameSpace')."admin/{$parentPlural}/{$parentRoute}.general.menuTitle")</span>
                                @if($hasInnerMenus)
                                    <span class="fa arrow-after ml-auto pl-2 fa-angle-left"></span>
                                @endif
                            </a>
        @if($hasInnerMenus)

            <ul class="sub-menu  flex-column list-unstyled  collapse @if($activeParent) show   @endif " id="collapse_{{$parentPlural}}">
                    @foreach($node['menus'] as $subMenu)
                    @continue($isExcludedFromConfFile= in_array( $subMenu,\Illuminate\Support\Arr::get($node,'excludeFromSideBar',[])))

                    @php($canSeeSubMenu=($subMenu == $parentRoute) ?$canSeeMenu:auth()->user()->can('admin.index-'.$parentRoute.ucfirst($subMenu)))

                    @if(config($packageVariables->get('package').'.main.permissionsCheckOnSideBar',true)? $canSeeSubMenu :true )
                        @include($packageVariables->get('blades').'admin._includes.sidebar._mainSubItem')
                    @endif
                @endforeach
                 </ul>
        @endif
          </li>


    @includeWhen(\Illuminate\Support\Arr::get($node, 'separatorAfter',false) ,$packageVariables->get('blades').'admin._includes.sidebar._separatorLine')

@endif


@php

    $parentPlural = isset($node['plural']) ? $node['plural'] : \Illuminate\Support\Str::plural($parentRoute);
    $parentPermission="admin.index-{$parentRoute}";
    $hasInnerMenus=\Illuminate\Support\Arr::has($node, 'menus') ;
     //Gather All Permissions In Order To Find if User Can se the Item
    //**************
    $canSeeMenu=(auth()->user()->can($parentPermission)  );
   if($hasInnerMenus ){
        $children= array_keys( $node['menus'] );

        $childPermissions= array_map(function ($child) use ($parentRoute) {
            return 'admin.index-'.$child;
        }, $children);
       $canSeeMenu= auth()->user()->can(array_merge([$parentPermission], $childPermissions));
   }
  //**************

@endphp

@if(config('baseAdmin.main.permissionsCheckOnSideBar',true)? $canSeeMenu : true )
    @php
        if(!$hasInnerMenus ){
            $route=\Illuminate\Support\Arr::get($node, 'route', false);

                if(  $route and Route::has('admin.'.$route) ){
                    $node['url']=route('admin.' . $route);
                }
                $node['url']=\Illuminate\Support\Arr::get($node,'url','#');
                $routes=collect(['url',$node['url']]);

         }
       else{
                 //Normalize All subItems To have Url and Translation
                 //**************
               $routes = collect(\Illuminate\Support\Arr::get($node, 'menus',[]))->transform(function (&$item) use ($packageVariables) {
                     $route = Str::replaceFirst('admin.', '', \Illuminate\Support\Arr::get($item, 'route', false));
                     if ($route and Route::has('admin.'.$route)) {
                         $item['url'] = route('admin.' . $route);
                     }
                     $item['trans'] = __($packageVariables->get('nameSpace').'admin/' . \Illuminate\Support\Arr::get($item,'trans','no_translation'));

                     return $item;
                 })->filter(function ($item) {
                     return \Illuminate\Support\Arr::has($item, 'url');
                 });
                $node['menus']=$routes->toArray();
                 //**************
    }
    @endphp
    <li class=" nav-item @if($activeParent=($routes->contains('url', url()->current()) or \Illuminate\Support\Str::contains( url()->current(),$routes->pluck('url')->toArray()))) active  @endif ">

        <a class="px-3 nav-link  d-flex align-items-center" href="{{$hasInnerMenus?'#': $node['url'] }}"
           @if($activeParent and $hasInnerMenus) aria-expanded="true" @endif
           @if($hasInnerMenus) data-toggle="collapse" data-target="#collapse_{{$parentPlural}}" role="button" aria-expanded="false"
           aria-controls="collapse_{{$parentPlural}}" @endif>
            @isset($node['icon'])
                @php($icon = $node['icon'])
                <i class=" {{ \Illuminate\Support\Arr::get($icon,'class')}}  mr-2 pt-1 align-self-start"
                   style=" {{ \Illuminate\Support\Arr::get($icon,'style')}}"></i>
            @endisset
            <span class="title "> @lang($packageVariables->get('nameSpace')."admin/".\Illuminate\Support\Arr::get($node,'trans','no_translation'))</span>
            @if($hasInnerMenus)
                <span class="fa arrow-after ml-auto pl-2 fa-angle-left"></span>
            @endif
        </a>
        @if($hasInnerMenus)

            <ul class="sub-menu  flex-column list-unstyled  collapse @if($activeParent) show   @endif " id="collapse_{{$parentPlural}}">
                @foreach($node['menus'] as $subMenuKey=>$subMenu)

                    @php($canSeeSubMenu=($subMenu == $parentRoute) ? $canSeeMenu:auth()->user()->can('admin.index-'.$subMenuKey))

                    @if(config('baseAdmin.main.permissionsCheckOnSideBar',true)? $canSeeSubMenu :true )
                        @include($packageVariables->get('blades').'admin._includes.sidebar._customSubItem')
                    @endif
                @endforeach
            </ul>
        @endif
    </li>

@endif


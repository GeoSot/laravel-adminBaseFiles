@php
    use Illuminate\Support\Collection;
     /**
     * @var Collection $packageVariables
     */
     $snippetsDir=$packageVariables->get('blades').'admin._includes.listDataParsingSnippets.';
@endphp
<section class="navbar-nav sidebar  flex-fill py-4">
    <ul id="menu" class="page-sidebar-menu flex-column  nav">
        <li class="  nav-item @if(Route::is("admin.dashboard")) active @endif">
            <a class=" d-flex align-items-center px-3 nav-link" href="{{ route('admin.dashboard') }}">
                <i class="icon ion-ios-home fa-lg mr-2"></i>
                <span class="title"> {{trans_with_fallback('admin/generic.menu.dashboard')}}</span>
            </a>
        </li>
        @include($packageVariables->get('blades').'.admin._includes.sidebar._separatorLine')
        @php
            $routes=collect(config($packageVariables->get('package').'.main.routes'))->sortBy('order');
            $customMenuItems=collect(config($packageVariables->get('package').'.main.customMenuItems'))->sortBy('order');

            //MERGE THE TWO COLLECTION So WE CAN PUT THEM IN ORDER
            //**************
            $mergeCollection=collect([]);
            $routes->each(function ($item,$key)  use($mergeCollection){
            $mergeCollection->put($key,['isCustomMenu'=>false,'order'=>data_get($item,'order',9999)]);
            });
            $customMenuItems->each(function ($item,$key)  use($mergeCollection){
            $mergeCollection->put($key,['isCustomMenu'=>true,'order'=>data_get($item,'order',9999)]);
            });
            //****************************


        @endphp

        @foreach($mergeCollection->sortBy('order') as $key=>$dt)
            @if($dt['isCustomMenu'])
                @include($packageVariables->get('blades').'admin._includes.sidebar._customItem',['parentRoute' =>$key, 'node'=>$customMenuItems->get($key)])
            @else
                @include( $packageVariables->get('blades').'admin._includes.sidebar._mainItem',['parentRoute' =>$key, 'node'=>$routes->get($key)])
            @endif
        @endforeach

    </ul>
</section>



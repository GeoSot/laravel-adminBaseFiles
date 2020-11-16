@php( $requestParams=$viewVals->get('params')->get('extra_filters'))


<div class="adminSidebar navbar   h-100  bg-admin navbar-dark px-0 align-items-start">
    <section class="navbar-nav sidebar  flex-fill ">
        <ul id="menu" class="page-sidebar-menu flex-column  nav">
            @foreach(\App\Models\Setting::getGrouped() as $groupName=>$group)
                <li class=" nav-item  ">
                      <a href="#" data-toggle="collapse" data-target="#collapse_settings_{{$groupName}}" role="button"
                         aria-expanded="{!!(\Illuminate\Support\Arr::get($requestParams,'group')==$groupName)?'true':'false'!!}"
                         aria-controls="collapse_settings_{{$groupName}}"
                         class="px-3  nav-link  d-flex align-items-center">
                          <span class="title ">{{ (empty($groupName) ?? is_null($groupName)) ? 'Uncategorised':ucfirst($groupName) }}</span> <span
                                  class="fas arrow-after ml-auto pl-2 fa-angle-left"></span>
                      </a>
                      <ul id="collapse_settings_{{$groupName}}"
                          class="sub-menu  flex-column list-unstyled  collapse @if($groupIsOpen=(\Illuminate\Support\Arr::has($requestParams,'group') and \Illuminate\Support\Arr::get($requestParams,'group')==$groupName)) show @endif ">
                            @foreach( $group as $subGroupName=>$subGroup)
                              <li class="pl-3 nav-item @if($groupIsOpen and \Illuminate\Support\Arr::get($requestParams,'group')==$groupName and \Illuminate\Support\Arr::get($requestParams,'sub_group')==$subGroupName) active @endif ">
                                  <a href="{{route($viewVals->get('baseRoute').'.index',['extra_filters'=>['group'=>$groupName,'sub_group'=>$subGroupName]])}}"
                                     class="nav-link px-3 inner-level-link d-flex align-items-center">
                                      <i class="fas fa-angle-double-right mr-2"></i>
                                      <span class="title">{{(empty($subGroupName) ?? is_null($subGroupName)) ? 'Uncategorised':ucfirst($subGroupName)}}</span>
                                  </a>
                              </li>

                          @endforeach
                      </ul>
                </li>
            @endforeach
        </ul>
    </section>
</div>

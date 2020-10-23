@php
    use Illuminate\Support\Collection;
    use Illuminate\View\Factory;
     /**
     * @var Collection $packageVariables
     * @var Factory $__env
     */
$title= ( isset($viewVals) and $viewVals->has('modelLang'))? __($viewVals->get('modelLang').'.general.menuTitle'):
$pushedTitle=$__env->getSection('documentTitle', __($packageVariables->get('nameSpace').'admin/generic.menu.dashboard'))
@endphp


<section class=" d-flex  flex-wrap justify-content-between align-items-center shadow-sm py-2 px-3  border-bottom">

    <h1 class="pageTitle h4 text-dark my-0 font-weight-normal">{!! $pushedTitle ?? $title !!}</h1>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent p-0 mb-0 small ">
            <li class="breadcrumb-item ">
                <a href="{{route('admin.dashboard')}}">
                    <i class="fa fa-home fa-lg mr-2 text-dark"></i>@lang($packageVariables->get('nameSpace').'admin/generic.menu.dashboard')
                </a>
            </li>
            @if(isset($viewVals) and $viewVals->has('breadCrumb'))
                @foreach($viewVals->get('breadCrumb') as $title=>$route)
                    @if($loop->last or !$route)
                        <li class="breadcrumb-item active">@lang($title)</li>
                    @else
                        <li class="breadcrumb-item ">
                            <a href="{{$route}}">@lang($title)</a>
                        </li>
                    @endif
                @endforeach
            @endif
        </ol>
    </nav>
</section>

@php
    use Illuminate\Support\Collection;
     /**
     * @var Collection $viewVals
     */
     $params=$viewVals->get('params');
      $lang=$viewVals->get('baseLang');
@endphp

@if(!empty( request()->query()))
    <div class="mb-3">
        <a href="{{route($viewVals->get('baseRoute').'.index','clean-queries')}}" class="btn btn-outline-info  ">
            <span class=""> @lang("{$lang}.listFilters.cleanFilters")</span>
            <span class="btn-label btn-label-right"><i class="fas fa-sync"></i></span>
        </a>
    </div>
@endif

<!--listingFiltersBar script:indexPages.js-->
<form action="{{route($viewVals->get('baseRoute').'.index')}}" id="tableForm" class="main_form row mb-3 @] " method="GET">

    @if(!empty( $viewVals->get('extraValues')->get('searchable')))
        <div class="form-group   col-auto">
            <div class="input-group ">
                <input id="keyword" type="text" class="form-control" placeholder=" @lang("{$lang}.listFilters.search")" name="keyword"
                       value="{!! old('keyword', $params['keyword']) !!}">
                <div class="input-group-append">
                    <button class="btn border" type="button" data-click="js-submit-form" id="searchSubmit"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
    @endif

    @if(in_array('is_enabled',$viewVals->get('options')->get('fillable')) and in_array('is_enabled',$viewVals->get('extraValues')->get('listable')))
        <div class="form-group  col-auto">
            <select class="form-control  custom-select " name="status" id="status" data-change="js-submit-form">
                <option value=""> @lang("{$lang}.listFilters.selectStatus")</option>
                <option {{ ($params['status']==1) ? 'selected': '' }} value="1"> @lang("{$lang}.listFilters.statusEnabled")</option>
                <option {{ ($params['status']==-1) ? 'selected': '' }} value="-1"> @lang("{$lang}.listFilters.statusDisabled")</option>
            </select>
        </div>
    @endif
    <div class="form-group ml-auto col-auto ">
        <select class="form-control  custom-select " name="num_of_items" data-change="js-submit-form">
            @php($sessionNum=Session::get('num_of_items', \GeoSot\BaseAdmin\Helpers\Base::settings('admin.generic.paginationDefaultNumOfItems',100)))
            @foreach(\GeoSot\BaseAdmin\Helpers\Base::settings('admin.generic.paginationOptions',[10, 25, 50,100]) as $num)
                <option value="{{$num}}" {!! ($sessionNum==$num) ? 'selected="selected"': '' !!}>{{$num}}</option>
            @endforeach
        </select>
    </div>
    <input type="hidden" name="sort" id="sort" value="{{ $params['sort'] }}">
    <input type="hidden" name="order_by" id="order_by" value="{{ $params['order_by'] }}">
    @if($viewVals->get('options')->get('modelHasSoftDeletes'))
        <input type="hidden" name="trashed" id="trashed" value="{{ $params['trashed'] }}">
    @endif

    <div class="col-12">
        @include($packageVariables->get('blades').'admin._includes._listingFiltersBar_ExtraFilters')
    </div>

</form>
@if($viewVals->get('options')->get('modelIsExportable'))
    <a href="{{ request()->fullUrlWithQuery(['export'=>'csv'])}}" class="btn btn-outline-info btn-sm  ">
        <span class=""> @lang("{$lang}.listFilters.export")</span>
        <span class="btn-label btn-label-right"><i class="fas fa-download"></i></span>
    </a>
@endif



<!--listingFiltersBar END-->

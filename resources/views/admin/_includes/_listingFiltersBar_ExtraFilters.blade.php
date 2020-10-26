@php
    use Illuminate\Support\Collection;
    /**
    * @var Collection $viewVals
    * @var Collection $params
    * @var Collection $params
    */
    $lang=$viewVals->get('baseLang');
    $modelLang=$viewVals->get('modelLang');
    $filters=$viewVals->get('extra_filters');
    $requestParams=$params->get('extra_filters');
    $requestHasExtraFilters=$requestParams->filter(function ($it){ return is_array($it)?!empty(array_filter($it)):!is_null($it);})->isNotEmpty()
@endphp
@if($filters->count())
    <div class="px-5">
        <hr class=" mt-2 mb-1">
    </div>
    <div class="text-right">
        <button class="btn btn-outline-secondary btn-sm" type="button" data-toggle="collapse" data-target="#extraFilters_collapse" aria-expanded="false"
                aria-controls="extraFilters_collapse">
            @lang($lang.'.button.extraFilters')
        </button>
    </div>

    <div class="collapse {{($requestHasExtraFilters or \GeoSot\BaseAdmin\Helpers\Base::settings('admin.generic.keepExtraFiltersOpen', false))? 'show':''}}"
         id="extraFilters_collapse">

        <div class="row">
            @foreach($filters as  $name=>$filter)
                @php/** @var array $filter/ @endphp
                @php($filterType=Arr::get($filter,'type'))
                @if( in_array($filterType,['boolean','bool','hasValueInside']))
                    <div class="form-group   input-group-sm d-inline-block  col-auto  ">
                        <label for="extra_filters[{{$name}}]" class="small control-label"> @lang($modelLang.'.filters.'.$name)</label>
                        <select class="form-control custom-select-sm custom-select" name="extra_filters[{{$name}}]">
                            <option value=""> @lang($lang.'.formTitles.formSelectPlaceHolder')</option>
                            @foreach(['true'=>1,'false'=>0] as $key=>$val)
                                <option @if(in_array($val,(array)Arr::get($requestParams,$name)))selected="selected"
                                        @endif value="{{$val}}"> @lang("$lang.listFilters.$key")</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if(in_array($filterType,['multiSelect','select']))
                    @php($isMultiSelect=(Arr::get($filter,'type') =='multiSelect'))
                    <div class="form-group   input-group-sm d-inline-block  col-auto  ">
                        <label for="extra_filters[{{$name}}]" class="small control-label"> @lang($modelLang.'.fields.'.$name)</label>
                        <select class="form-control custom-select-sm custom-select" name="extra_filters[{{$name}}]@if($isMultiSelect)[]@endif" @if($isMultiSelect) multiple @endif>
                            @if((Arr::get($filter,'type') =='select'))
                                <option value=""></option>
                            @endif
                            @foreach(Arr::get($filter,'values',[]) as $key=> $val)
                                <option @if(in_array($key,(array)Arr::get($requestParams,$name)))selected="selected" @endif value="{{$key}}">{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                @if(in_array($filterType,['dateTime']))
                    <div class="form-group   d-inline-block  col-auto ">
                        <label for="extra_filters[{{$name}}]" class="small control-label"> @lang($modelLang.'.fields.'.$name)</label>
                        <div data-toggle="calendar" data-name="extra_filters[{{$name}}]" data-locale="DD/MM/YYYY" class="input-group  input-group-sm">
                            <div class="flex-fill input-group-sm">
                                <input name="extra_filters[{{$name}}]" type="hidden" value="{{Arr::get($requestParams,$name)}}" id="{{$name}}">
                                <input readonly="readonly" name="extra_filters[{{$name}}_formatted]" type="text" class="form-control bg-light"
                                       value="{{optional(Arr::get($requestParams,$name))->format('d/m/Y') ?? ''}}">
                            </div>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-secondary "><i class="fa fa-calendar"></i></button>
                            </div>
                        </div>
                    </div>
                @endif

                @if(in_array($filterType,['dateRange']))
                    @php($hasValues=!empty(Arr::get($requestParams,$name.'.start').Arr::get($requestParams,$name.'.end')))
                    @php($formattedValue=optional(Arr::get($requestParams,$name.'.start'))->format('d/m/Y') .' - ' .optional(Arr::get($requestParams,$name.'.end'))->format('d/m/Y'))
                    <div class="form-group   d-inline-block  col-auto ">
                        <div class="d-flex justify-content-between">
                            <label for="extra_filters[{{$name}}]" class="small control-label"> @lang($modelLang.'.fields.'.$name)</label>
                            <button type="button" class="btn btn-sm btn-outline-light " data-clear="dateRangeCalendar" data-name="extra_filters[{{$name}}]">
                                <i class="fa-fw fa fa-eraser"></i>
                            </button>
                        </div>
                        <div data-toggle="dateRangeCalendar" data-name="extra_filters[{{$name}}]" data-locale="DD/MM/YYYY" class="input-group  input-group-sm">
                            <div class="flex-fill input-group-sm">
                                <input name="extra_filters[{{$name}}][start]" type="hidden" value="{{Arr::get($requestParams,$name.'.start')}}"
                                       id="{{$name}}_start">
                                <input name="extra_filters[{{$name}}][end]" type="hidden" value="{{Arr::get($requestParams,$name.'.end')}}" id="{{$name}}_end">
                                <input readonly="readonly" name="extra_filters[{{$name}}_formatted]" type="text" class="form-control bg-light mr-4"
                                       value="{!! $hasValues?$formattedValue : '' !!}">
                            </div>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-secondary "><i class="fa fa-calendar fa-fw"></i></button>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="text-right ">
            @if($requestParams->filter(function ($it){return !is_null($it);})->isNotEmpty())
                <button type="button" data-toggle="clearExtraFilters" class="btn btn-outline-info btn-sm mx-1">
                    @lang($lang.'.button.clearExtraFilters')
                    <span class="btn-label btn-label-right"><i class="fa fa-refresh"></i></span>
                </button>
            @endif
            <button type="submit" class="btn btn-admin btn-sm ,x-1">
                @lang($lang.'.button.search')
                <span class="btn-label btn-label-right"><i class="fa fa-fw fa-search"></i></span>
            </button>
        </div>
        <div class="">
            <hr class=" mt-2 mb-1">
        </div>
    </div>

    @push('scripts')
        <script defer data-comment="extra_filters on index page">
            jsHelper.base.execute(() => {
                let clearFiltersBrn = '[data-toggle="clearExtraFilters"]';
                $(document).on('click', clearFiltersBrn, function () {
                    let $filterInputs = $('[name^="extra_filters\["]');
                    $filterInputs.val('').trigger('change');
                    $(clearFiltersBrn).parents('form').submit();
                });
            })
        </script>
    @endpush
@endif

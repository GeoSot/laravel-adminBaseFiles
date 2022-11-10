@extends($packageVariables->get('adminLayout'))

@component($packageVariables->get('blades').'admin._components.listingButtons',['viewVals'=>$viewVals, ]  )

    @slot('afterRight')
        @php($cleanView=route('admin.settings.index',['extra_filters'=>['group'=>'',],'keyword'=>'',]))
        @if(\Illuminate\Support\Facades\URL::full()==  route('admin.settings.index'))
            <a href="{{$cleanView}}" class="btn btn-outline-admin">See all in one Page</a>
        @else
            <a href="{{route('admin.settings.index')}}" class="btn btn-outline-admin">Return to Default view</a>
        @endif

    @endslot
@endcomponent
@section('content')

    <div class="row flex-fill ">
        <aside id="settingsSideBar" class="flex-fill px-1 col-12 col-sm-4 col-md-4 col-lg-3 col-xl-2">
            @if(Arr::get(\Illuminate\Support\Facades\Request::query(),'extra_filters.group') || !array_key_exists('keyword', \Illuminate\Support\Facades\Request::query()))
                @include($packageVariables->get('blades').'admin.settings._sideBar')
            @endif
        </aside>

        <div class="col-12  col-sm-8 col-md-8 col-lg-9 col-xl-9 offset-xl-1 ">

            <div class="card">
                <div class="card-body">

                    <div class="table-responsive ">
                        <table class="table  table-hover  ">

                            <thead class="table-sm bg-danger text-white">
                            <tr>
                                <th scope="col">
                                    @include($packageVariables->get('blades').'admin._includes._selectAllBtn',['target'=>'col1','class'=>'mx-2'])
                                </th>
                                @foreach( $listable=$viewVals->get('extraValues')->get('listable') as $name)
                                    <th scope="col" class="{{$name}}  " data-name="{{$name}}">
                                        <div class="d-flex justify-content-between flex-wrap p-1">
                                            <span>	 @lang("{$viewVals->get('modelLang')}.fields.{$name}")</span>
                                        </div>
                                    </th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($viewVals->get('records') as $record)
                                <tr @if($record->notes)data-toggle="popover" data-content="{{$record->notes}}" data-trigger="hover" data-html="true" data-placement="bottom" data-container="body"  @endif>
                                    @include($packageVariables->get('blades').'admin._includes._listDataParsing_helper', ['parse'=>'firstCheckBox'])
                                    @foreach($listable=$viewVals->get('extraValues')->get('listable') as $listName)
                                        @include($packageVariables->get('blades').'admin._includes._listDataParsing_helper', ['parse'=>'fields'])
                                    @endforeach
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                    @component($packageVariables->get('blades').'admin._components.listingPagination',['viewVals'=>$viewVals, ]  )    @endcomponent

                </div>
            </div>

        </div>
    </div>


    <div class="card mt-5">
        <div class="card-body">
            <div class="text-center mouse-pointer" data-toggle="collapse" data-target="#extraSettingFilters">
                Extra Filters <small class="text-muted">(It gets messy)</small>
            </div>
        </div>
        <div class="card-body collapse" id="extraSettingFilters">
            @component($packageVariables->get('blades').'admin._components.listingFiltersBar',['viewVals'=>$viewVals, ] )@endcomponent
        </div>
    </div>

@endsection

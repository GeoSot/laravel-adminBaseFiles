@extends($packageVariables->get('adminLayout'))
@component($packageVariables->get('blades').'admin._components.listingButtons',['viewVals'=>$viewVals, ]  )   @endcomponent

@section('content')

    <div class="row flex-fill ">
            <aside id="settingsSideBar" class="flex-fill px-1 col-12 col-sm-4 col-md-4 col-lg-3 col-xl-2">
                @include($packageVariables->get('blades').'admin.settings._sideBar')
            </aside>

            <div class="col-12  col-sm-8 col-md-8 col-lg-9 col-xl-9 offset-xl-1 ">

                    <div class="card">
                        <div class="card-body">

                                <div class="table-responsive ">
                                    <table class="table  table-hover  ">

                                        <thead class=" bg-danger text-white">
                                            <tr>
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
                                                <tr>
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

@endsection

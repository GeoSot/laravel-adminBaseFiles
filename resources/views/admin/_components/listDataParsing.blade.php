@if(isset($buttonsSlot))
    {!!$buttonsSlot !!}
@else
    @component($packageVariables->get('blades').'admin._components.listingButtons',['viewVals'=>$viewVals, ]  )   @endcomponent
@endif

<div class=" card row">
    <div class=" card-body col-12">
         @if(isset($filtersSlot))
            {!!$filtersSlot !!}
        @else
            @component($packageVariables->get('blades').'admin._components.listingFiltersBar',['viewVals'=>$viewVals, ] )@endcomponent
        @endif

        @if($viewVals->get('records')->count())
            <div class="table-responsive">
                <table id="recordsList" class="table table-striped table-hover table-sm table-bordered ">
                     @include($packageVariables->get('blades').'admin._includes._listingTableHead')
                    <tbody>

                      @if(isset($tableData))
                          {!! $tableData !!}
                      @endif

                    </tbody>
                </table>
            </div>
            @if(isset($paginationSlot))
                {!!$paginationSlot !!}
            @else
                @component($packageVariables->get('blades').'admin._components.listingPagination',['viewVals'=>$viewVals, ]  )    @endcomponent
            @endif

        @else
            <div class="text-center"> @lang($packageVariables->get('nameSpace').'admin/generic.listMessages.noResults')</div>
        @endif
    </div>
</div>

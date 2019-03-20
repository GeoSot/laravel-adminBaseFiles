<!--listingPagination -->
@php
    $params=$viewVals->get('params');
     $lang=$viewVals->get('baseLang');

@endphp
<hr class="mx-4 my-4 ">
<div class="d-flex justify-content-between">

	<div class="paginationResults">
			 @lang($packageVariables->get('nameSpace').'pagination.items', ['pageitems'=> $viewVals->get('records')->count(), 'totalitems' =>$viewVals->get('records')->total() ] )
	</div>
	{!! $slot !!}
	<div class="paginations ">{!! $viewVals->get('records')->appends($params->toArray())->render() !!}
	</div>
</div>
<!--listingPagination END-->

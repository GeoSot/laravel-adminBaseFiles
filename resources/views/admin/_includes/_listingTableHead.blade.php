<!--listingTableHead-->
@php

    use Illuminate\Support\Collection;
    /**
    * @var Collection $viewVals
    */

    $params=$viewVals->get('params');
    $modelLang=$viewVals->get('modelLang');
    $baseLang=$viewVals->get('baseLang');
    $listable=$viewVals->get('extraValues')->get('listable');
    $sortable=$viewVals->get('extraValues')->get('sortable')

@endphp


 <thead class="bg-info text-white ">
<tr class="">
		<th scope="col">
            @include($packageVariables->get('blades').'admin._includes._selectAllBtn',['target'=>'col1','class'=>'mx-2'])
		</th>
    @foreach($listable as $name)
        <th scope="col" class="{{$name}} align-top " data-name="{{$name}}">
                 <div class="d-flex justify-content-between flex-wrap p-1 mr-1">
                     <span>	{!!__( "{$modelLang}.fields.{$name}" )!!}</span>
                     @if(in_array($name,$sortable))
                         <div class="sortingControls d-flex">
                             <span class=" mouse-pointer @if ($params['order_by']==$name  and $params['sort']=='asc' ) sorted asc text-warning @endif" data-order="{{$name}}"
                                   data-sort="asc">
                                    <i class="fas fa-sort-amount-asc sorting_asc "></i>
                             </span>
                              <span class="ml-1 mouse-pointer @if ($params['order_by']==$name  and $params['sort']=='desc' ) sorted desc text-warning @endif" data-order="{{$name}}"
                                    data-sort="desc">
                                    <i class="fas fa-sort-amount-desc sorting_asc"></i>
                              </span>
                                </div>
                            </div>
            @endif

			</th>

    @endforeach
</tr>
 </thead>
<!--listingTableHead END-->


<!--listingTableHead-->

<tr class="">
	   <th></th>
    @foreach($viewVals->get('roles') as $role)
        <th class="align-top">

             @if($role->name=='god' )<!--and Auth::user()->can('handle-permissions')-->
                 <span class=""> {{$role->display_name}}</span>
                 @else
                     <div class="custom-control custom-checkbox mx-2">
                       <input id="select_all_{{$role->id}}" type="checkbox" class="custom-control-input" data-target="col-{{$role->id}}" data-toggle="select-all"/>
                       <label for="select_all_{{$role->id}}" class="custom-control-label d-flex"> {{$role->display_name}}</label>
                   </div>
                 @endif
        </th>
    @endforeach
</tr>

<!--listingTableHead END-->


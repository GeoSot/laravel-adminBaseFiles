@foreach($viewVals->get('records') as $key=>$group)

    <tr class="  table-secondary">
       <td colspan="100%" class=" "> <div class="py-1  px-3 m-0 h5">{{ucfirst($key)}}</div></td>
    </tr>
    @foreach($group as $subKey=>$subGroup)

        <tr class="text-strong  ">
       <td colspan="100%" class="table-dark  px-2"> {{$subKey}} <span class="small">({{$key}})</span></td>
    </tr>
        @foreach($subGroup as $permission)

            <tr>
            <td>
                <div>
                    <a href="{!! route($viewVals->get('baseRoute').'.edit', $permission) !!}">
                        <i class="fa  fa-cog fa-lg"></i>
                    </a>

                    <span data-placement="top" data-toggle="tooltip"
                          title="{{$permission->description}}"> {{str_replace(['-'.$subKey,$key.'.'],'', $permission->name)}}</span>
                </div>
            </td>
                @foreach($viewVals->get('roles') as $role)
                    <td class="">
                        @if($role->name=='god' )
                            @if($role->hasPermission($permission->name) )
                                <i class="fa fa-check text-success" aria-hidden="true"></i>
                            @else
                                <i class="fa fa-times text-danger" aria-hidden="true"></i>
                            @endif
                            <input type="hidden" name="role_permissions[{{$role->id}}][]" value="{{$permission->id}}">

                        @else
                            <div class="custom-control custom-checkbox mx-2 ">
                               <input id="select_all_r{{$role->id}}_p{{$permission->id}}" name="role_permissions[{{$role->id}}][]" value="{{$permission->id}}"
                                      type="checkbox"
                                      class="custom-control-input" data-select-all="col-{{$role->id}}"
                                      @if($role->hasPermission($permission->name) ) checked="checked" @endif/>
                               <label for="select_all_r{{$role->id}}_p{{$permission->id}}" class="custom-control-label d-flex">  &nbsp;</label>
                           </div>
                        @endif
                      </td>
                @endforeach
                     </tr>

        @endforeach

    @endforeach
@endforeach
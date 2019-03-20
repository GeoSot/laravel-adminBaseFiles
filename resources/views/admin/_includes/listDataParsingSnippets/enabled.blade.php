<div class="text-center">
          @php
              $options=$viewVals->get('options')->get('indexActions');
              $modelClass=lcfirst($viewVals->get('modelClassShort'));
              $baseRoute=$viewVals->get('baseRoute');
              $isEnabled=data_get($record,$listName);
          @endphp
    @if($isEnabled)
        @if (in_array('enable',$options)and auth()->user()->can('admin.update-'.$modelClass))
            <button data-toggle="listing-actions-status" data-method="POST" data-keyword="disable" data-url="{{ route("{$baseRoute}.change.status") }}"
                    data-id="{{$record->id}}"
                    type="button" class="btn btn-sm rounded-circle btn-outline-success p-0">
                    <span class="fa fa-fw fa-check  fa-lg"></span>
                </button>
        @else
            <span class="fa fa-check text-success"></span>
        @endif
    @else
        @if(in_array('disable',$options) and auth()->user()->can('admin.update-'.$modelClass))
            <button data-toggle="listing-actions-status" data-method="POST" data-keyword="enable" data-url="{{ route("{$baseRoute}.change.status") }}" data-id="{{$record->id}}"
                    type="button" class="btn btn-sm rounded-circle btn-outline-danger p-0">
                    <span class="fa fa-fw fa-close  fa-lg "></span>
                </button>
        @else
            <span class="fa fa-close text-danger"></span>
        @endif
    @endif
    </div>

<div class="text-center">
          @php
              $options=$viewVals->get('options')->get('indexActions');
              $modelClass=lcfirst($viewVals->get('modelClassShort'));
              $baseRoute=$viewVals->get('baseRoute');
              $isEnabled=data_get($record,$listName);
          @endphp
    @if($isEnabled)
        @if (in_array('enable',$options)and auth()->user()->isAbleTo('admin.update-'.$modelClass))
            <button data-toggle="listing-actions-status" data-method="POST" data-keyword="disable" data-url="{{ route("{$baseRoute}.change.status") }}"
                    data-id="{{$record->id}}"
                    type="button" class="btn btn-sm rounded-circle btn-outline-success p-0">
                    <span class="fas fa-fw fa-check  fa-lg"></span>
                </button>
        @else
            <span class="fas fa-check text-success"></span>
        @endif
    @else
        @if(in_array('disable',$options) and auth()->user()->isAbleTo('admin.update-'.$modelClass))
            <button data-toggle="listing-actions-status" data-method="POST" data-keyword="enable" data-url="{{ route("{$baseRoute}.change.status") }}" data-id="{{$record->id}}"
                    type="button" class="btn btn-sm rounded-circle btn-outline-danger p-0">
                    <span class="fas fa-fw fa-times  fa-lg "></span>
                </button>
        @else
            <span class="fas fa-times text-danger"></span>
        @endif
    @endif
    </div>

@foreach($record->get('records') as $job)
    <tr>
        <td>
            {{$job->id}}
        </td>
        <td>
            {!! json_decode($job->payload)->displayName !!}
        </td>
        <td>
            {{$job->queue}}
        </td>
        <td>
            {{$job->connection}}
        </td>
        <td>
          {{ $job->failed_at}}
        </td>


        <td>
         <div class="d-flex justify-content-between flex-">
             @if(auth()->user()->isAbleTo('admin.retry-job'))
                 <form action="{{route( $viewVals->get('modelRoute').".retry",$job->id)}}" method="POST" class="form-inline  ">
                    @method('PATCH')
                     @csrf
                     <button type="submit" class="btn  btn-primary btn-sm" title=" @lang("{$viewVals->get('modelLang')}.buttons.retry")">
                     <i class="fas fa-sync"></i>
                      </button>
                </form>
             @endif
             @if(auth()->user()->isAbleTo('admin.flush-job'))
                 <form action="{{route( $viewVals->get('modelRoute').".flush",$job->id)}}" method="POST" class="form-inline  ">
                    @method('PATCH')
                     @csrf
                     <button type="submit" class="btn  btn-warning btn-sm" title=" @lang("{$viewVals->get('modelLang')}.buttons.flush")">
                     <i class="fas fa-trash"></i>
                      </button>
                </form>
             @endif
             <button class="btn btn-secondary btn-sm" type="button" data-toggle="collapse" data-target="#collapse-failedJob_{{$job->id}}" aria-expanded="false"
                     aria-controls="collapse-failedJob_{{$job->id}}"
                     title=" @lang("{$viewVals->get('modelLang')}.buttons.details")">
                     <i class="fas fa-eye"></i>
                 </button>
         </div>
        </td>
    </tr>
    <tr class="collapse" id="collapse-failedJob_{{$job->id}}">
        <td colspan="6">
          <div class="pre-scrollable bg-light p-1">
              @foreach(preg_split('/\r\n|\r|\n/',$job->exception) as $block)
                  <code class=" d-block mb-3">{{$block}}</code>
              @endforeach
          </div>
        </td>
    </tr>

@endforeach

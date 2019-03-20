@if(   $viewVals->get('records')->get('failed_jobs')->get('records')->count())
    @push('topBar')
        <section class="button_actions card ">
		<div class=" card-body  py-2 d-flex ">

                @if(auth()->user()->can('admin.retry-job'))
                <form action="{{route( $viewVals->get('modelRoute').".retry",'all')}}" method="POST" class="form-inline  m-1">
                        @method('PATCH')
                    @csrf

                    <button type="submit" class="btn  btn-primary ">
                            <span class="btn-label"><i class="fa fa-refresh"></i></span>
                            <span class="btn_text"> @lang($packageVariables->get('nameSpace')."{$viewVals->get('modelLang')}.buttons.retryAll")</span>
                          </button>
                    </form>
            @endif
            @if(auth()->user()->can('admin.flush-job'))
                <form action="{{route( $viewVals->get('modelRoute').".flush",'all') }}" method="POST" class="form-inline  m-1">
                          @method('PATCH')
                    @csrf
                    <button type="submit" class="btn  btn-warning  ">
                        <span class="btn-label"><i class="fa fa-remove"></i></span>
                        <span class="btn_text"> @lang($packageVariables->get('nameSpace')."{$viewVals->get('modelLang')}.buttons.flushAll")</span>
                      </button>
                    </form>
            @endif


		</div>
	</section>
    @endpush

@endif

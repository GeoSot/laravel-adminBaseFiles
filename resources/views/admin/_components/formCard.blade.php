<!--com_formCard.blade -->
<div class="card ">
    <div class="card-header  {{ $class ?? 'bg-primary' }} text-white d-flex justify-content-between align-items-center py-1 pr-0">
        <div class="title h5 mb-0 font-weight-normal">@isset($title) {!!$title !!} @endisset</div>
        <button data-toggle="collapse" data-target="#{{$randId=uniqid('collapse_')}}" aria-expanded="true" aria-controls="{{$randId}}" type="button" class="btn text-reset">
            <i class="fa fa-angle-up fa-lg " aria-hidden="true"></i>
        </button>
    </div>
    <div class="collapse @if(!isset($show) or $show==true) show @endif" id="{{$randId}}">
        <div class="card-body">
            {!! $slot !!}
        </div>
    </div>
</div>
    <!--com_formCard.blade end-->

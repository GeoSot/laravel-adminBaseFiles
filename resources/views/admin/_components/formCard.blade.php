<!--com_formCard.blade -->
<div class="card ">
    @isset($title)
        <div class="card-header  {{ $class ?? 'border-bottom border-primary' }} text-primary d-flex justify-content-between align-items-center py-1 pr-0 mouse-pointer"
             data-toggle="collapse" data-target="#{{$randId=uniqid('collapse_')}}" aria-expanded="true" aria-controls="{{$randId}}">
            <div class="title h5 mb-0 font-weight-normal">@isset($title) {!!$title !!} @endisset</div>
            <span class="btn text-reset">
            <i class="fa fa-angle-up fa-lg " aria-hidden="true"></i>
        </span>
        </div>
    @endisset
    @isset($title)
        <div class="collapse @if(!isset($show) or $show==true) show @endif" id="{{$randId}}">
            @endisset
            <div class="card-body">
                {!! $slot !!}
            </div>
            @isset($title)
        </div>
    @endisset
</div>
<!--com_formCard.blade end-->

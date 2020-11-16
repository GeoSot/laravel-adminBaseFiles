<!--com_formCard.blade -->
@php($randId='collapse_'.\Illuminate\Support\Str::uuid())
<div class="card ">
    @isset($title)
        <div class="card-header  {{ $class ?? 'border-bottom border-primary' }} text-primary d-flex justify-content-between align-items-center py-1 pr-0 mouse-pointer"
             data-toggle="collapse" data-target="#{{$randId}}" aria-expanded="true" aria-controls="{{$randId}}">
            <div class="title h5 mb-0 font-weight-normal">@isset($title) {!!$title !!} @endisset</div>
            <span class="btn text-reset">
            <i class="fas fa-angle-up fa-lg " aria-hidden="true"></i>
        </span>
        </div>
    @endisset

    <div class="collapse @if($show??true) show @endif @if(!isset($title)) show @endif" id="{{$randId}}">
        @isset($slot)
            <div class="card-body">
                {!! $slot !!}
            </div>
        @endisset
        @isset($footer)
            <div class="card-footer d-flex flex-wrap">
                {!! $footer !!}
            </div>
        @endisset
    </div>
</div>
<!--com_formCard.blade end-->

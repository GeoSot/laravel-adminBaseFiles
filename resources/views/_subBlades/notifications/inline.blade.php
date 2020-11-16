<div id="{{$uuid ?? ''}}" role="alert"
     class="my-3 alert alert-{{$alertClass}} @if($isDismissible) alert-dismissible @endif fade show">
    @if($isDismissible)
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    @endif
    @if($title)
        <div class="alert-heading "><strong>{!! $title !!}</strong></div>
        <hr>
    @endif
    @if($msg)
        <p class="mb-0">{!! $msg !!}</p>
    @endif
</div>

<div id="{{$uuid}}" class="toast w-100" role="alert" aria-live="assertive" aria-atomic="true" @if(!$isDismissible) data-autohide="false" @endif data-delay="1500">

    <div class="toast-header bg-{{$alertClass}}">
        @if($title)
            <div class=" mr-2"><strong>{!! $title !!}</strong></div>
        @endif
        <button type="button" class="ml-auto mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @if($msg)
        <div class="toast-body">{!! $msg !!}</div>
    @endif
</div>



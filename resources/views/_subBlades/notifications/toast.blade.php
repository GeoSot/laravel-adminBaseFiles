@push('toasts')
    <div id="{{$uuid}}" class="toast w-100" role="alert" aria-live="assertive" aria-atomic="true" @if(!$isDismissible) data-autohide="false" @endif data-delay="{{\GeoSot\BaseAdmin\Helpers\Alert::AUTO_HIDE_TIME}}">

        <div class="toast-header border-bottom-0  border-{{$alertClass}}" style="border-left: 7px solid">
            @if($title)
                <div class=" text-{{$alertClass}} mr-2"><strong>{!! $title !!}</strong></div>
            @endif
            <button type="button" class="ml-auto mb-1 close  text-{{$alertClass}}" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @if($msg)
            <div class="toast-body  border-{{$alertClass}}" style="border-left: 7px solid">{!! $msg !!}</div>
        @endif
    </div>
@endpush


@push('scripts')
    <script id="{{$uuid}}">
        jsHelper.jQuery.execute(() => {
            Swal.fire({
                title: '{!! $title !!}',
                text: '{!! $msg !!}',
                icon: '{{$alertClass}}',
                @if(!$isDismissible)
                timer: {{\GeoSot\BaseAdmin\Helpers\Alert::AUTO_HIDE_TIME}}
                @endif
            });
        })
    </script>
@endpush


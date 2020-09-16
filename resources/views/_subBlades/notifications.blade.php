<div class="notificationsWrapper">
    @if ($errors->any())
        <div class="alert alert-danger my-2  alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul class="list-unstyled small">
                @foreach ($errors->all() as $error)
                <li>{!! $error !!}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if ( Session::has(\GeoSot\BaseAdmin\Helpers\Alert::SESSION_KEY))
        @foreach(Session::get(GeoSot\BaseAdmin\Helpers\Alert::SESSION_KEY) as $alert)
            @php
                $alertType=\Illuminate\Support\Arr::get($alert,'type', false);
                $isDismissible=\Illuminate\Support\Arr::get($alert,'isDismissible', false);
                $title=\Illuminate\Support\Arr::get($alert,'title', '');
                $msg=\Illuminate\Support\Arr::get($alert,'msg', '');
                $alertClass=\Illuminate\Support\Arr::get($alert,'class', 'info');
                $uuid='uniqid'.\Illuminate\Support\Arr::get($alert,'uuid', uniqid());
            @endphp
            @if( $alertType== 'inline')
                <div id="{{$uuid}}" role="alert"
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
            @else
                @push('scripts')
                    @php($alertClass=($alertClass=="danger")?'error':$alertClass)
                    <script>
                        if (typeof toastr['{{$alertClass}}'] === 'function') {
                            toastr['{{$alertClass}}']('{!! addslashes( trim($msg) )!!}', '{!! trim($title) !!}');
                        }
                    </script>
                @endpush
            @endif
        @endforeach
        {{  Session::forget(GeoSot\BaseAdmin\Helpers\Alert::SESSION_KEY)}}
    @endif

</div>

{{--setTimeout(function () {--}}
{{--$alert.fadeOut(800, function () {--}}
{{--$wrapper.remove();--}}
{{--});--}}
{{--}, 4000);--}}

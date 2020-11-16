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
                $alertData=  [ 'isDismissible'=>\Illuminate\Support\Arr::get($alert,'isDismissible', false),
                                'title'=>\Illuminate\Support\Arr::get($alert,'title', ''),
                                'msg'=>\Illuminate\Support\Arr::get($alert,'msg', ''),
                                'alertClass'=>\Illuminate\Support\Arr::get($alert,'class', 'info'),
                                'uuid'=>'uniqid'.\Illuminate\Support\Arr::get($alert,'uuid', uniqid()),
                             ]
            @endphp

            @switch($alertType)
                @case(\GeoSot\BaseAdmin\Helpers\Alert::TYPE_SWEET_ALERT)
                @include($packageVariables->get('blades').'_subBlades.notifications.swal',$alertData)
                @break

                @case(\GeoSot\BaseAdmin\Helpers\Alert::TYPE_INLINE)
                @include($packageVariables->get('blades').'_subBlades.notifications.inline',$alertData)
                @break

                @default
                @include($packageVariables->get('blades').'_subBlades.notifications.toast',$alertData)
            @endswitch


        @endforeach


        {{  Session::forget(GeoSot\BaseAdmin\Helpers\Alert::SESSION_KEY)}}
    @endif

</div>

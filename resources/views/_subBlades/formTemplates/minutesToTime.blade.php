<!--minutesToTime -->
@if($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        <div {!! $options['wrapperAttrs'] !!} >
  @endif
            @endif


            @if ($showLabel && $options['label'] !== false && $options['label_show'])
                <?= Form::label($name, $options['label'], $options['label_attr']) ?>
            @endif
            @if ($showField)
                @php
                    $hours= intdiv( $options['value'] , 60 )?? 0;
                    $minutes=  fmod( $options['value'], 60 ) ?? 0;
                @endphp

                <div class="input-group" data-toggle="timePicker">
                <input type="number" class="form-control" min="0" data-trigger="timePicker" value="{{$hours}}">
                <div class="input-group-append">
                    <span class="input-group-text bg-white border-right-0 border-left-0 font-weight-bold px-1">:</span>
                </div>
                    <?= Form::input('hidden', $name, $options['value']) ?>
                    <input type="number" class="form-control" max="60" min="-1" data-trigger="timePicker" value="{{$minutes}}">
                <div class="input-group-append">
                    <span class="input-group-text"><i class="fa fa-fw fa-clock-o"></i> <span class="small">( hh:mm )</span></span>
                </div>
            </div>

                @include('laravel-form-builder::help_block')
            @endif

            @include('laravel-form-builder::errors')

            @if ($showLabel && $showField)
                @if ($options['wrapper'] !== false)
    </div>
    @endif
@endif


<!--minutesToTime  END -->

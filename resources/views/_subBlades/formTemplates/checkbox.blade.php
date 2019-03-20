@if($showLabel && $showField)
    @if($options['wrapper'] !== false)
        <div {!! $options['wrapperAttrs'] !!} >
    @endif
            @endif

            @if ($showField)
                @if ( \Illuminate\Support\Arr::get($options,'includeHidden',false))
                    <input type="hidden" value="0" name="{{$name}}">
                @endif
                {!! Form::checkbox($name, $options['value'], $options['checked'], $options['attr'])  !!}

            @endif

            @if ($showLabel && $options['label'] !== false && $options['label_show'])
                {!!  Form::customLabel($name, $options['label'], $options['label_attr']) !!}
            @endif
            @include('laravel-form-builder::help_block')
            @include('laravel-form-builder::errors')


            @if($showLabel && $showField)
                @if($options['wrapper'] !== false)
     </div>
    @endif
@endif

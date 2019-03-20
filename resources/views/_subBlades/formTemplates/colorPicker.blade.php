<!--colorPicker -->
@if($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        <div {!! $options['wrapperAttrs'] !!} >
  @endif
            @endif


            @if ($showLabel && $options['label'] !== false && $options['label_show'])
                <?= Form::label($name, $options['label'], $options['label_attr']) ?>
            @endif
            @if ($showField)

            <div class="input-group" data-toggle="colorPicker"   data-color="{{$options['value']}}">
                <?= Form::input($type, $name, $options['value'], $options['attr']) ?>
                <div class="input-group-append">
                    <span class="input-group-text  input-group-addon colorpicker-input-addon"><i></i></span>
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


<!--colorPicker  END -->

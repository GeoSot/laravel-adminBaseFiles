<!--daysField -->
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
                    if(!\Illuminate\Support\Arr::has($options['attr'],'disabled')){ $options['attr']['class'].=' bg-white';}
                     $options['attr']=array_merge([  'readonly' => 'readonly'],  $options['attr'])
                @endphp
                <div class="input-group mb-3" data-toggle="calendar" data-name="{{$name}}" data-locale="{{$options['cast']['js']??'DD/MM/YYYY'}}">
               <div class="flex-fill">
                   <?= Form::input('hidden', $name, $options['value'],[]) ?>
                   <?= Form::input($type, $name.'_formatted', ($options['value'] instanceof  \Carbon\Carbon)?$options['value']->format($options['cast']['php'] ?? 'd/m/Y'):'', $options['attr']) ?>
               </div>
                    <div class="input-group-append">
               <button class="btn btn-secondary " type="button" @if(\Illuminate\Support\Arr::has($options['attr'],['disabled','readonly']))  disabled @endif><i class="fa fa-calendar"></i></button>
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


<!--daysField  END -->

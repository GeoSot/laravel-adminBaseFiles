@php
    $wrapAttrs=  $options['wrapperAttrs'];
    $x=preg_match('/class="(.*?)"/', $wrapAttrs,$classContents );
    $withoutClass= preg_replace('/class=".*?"/', '', $wrapAttrs);
    $isSortable=\Illuminate\Support\Arr::get($options, 'sortable', false);
    $urlBtn=\Illuminate\Support\Arr::get($options, 'urlBtn', false);
    $isRepeatable=\Illuminate\Support\Arr::get($options, 'repeatable', false)
@endphp
@if ($showLabel && $showField)

    @if ($options['wrapper'] !== false)
        <div class="{!! $classContents[1] !!}   @if( $isSortable ) sortableItem @endif" {!! $withoutClass !!} >

    @endif

            @endif

            @if ($showLabel && $options['label'] !== false && $options['label_show'])
                {!! Form::customLabel($name, $options['label'], $options['label_attr'])  !!}
            @endif

            @if ($showField)
                @if($isRepeatable || $isSortable || $urlBtn   )
                    <div class="input-group  ">
                @endif
                        @if($isSortable )
                            <div class="input-group-prepend">
                                <span class="input-group-text sortingHandler mouse-pointer px-2"><i class="fas fa-sort"></i></span>
                             </div>
                        @endif

                        {!! Form::input($type, $name, $options['value'], array_merge( $options['attr'],\Illuminate\Support\Arr::has($options,'datalist')?['list'=>$name.'_datalist']:[])) !!}
                        @if($isRepeatable || $urlBtn)
                            <div class="input-group-append">
                              @if($isRepeatable)
                                    <button class="btn btn-danger ml-auto btn-sm" type="button" data-toggle="removeParent" data-target=".form-group"><i
                                                class="fas fa-minus"></i></button>
                                @endif
                                @if(($urlBtn===true and !empty($options['value'])) or !empty($urlBtn))
                                    <a href="{{($urlBtn===true)?$options['value']:$urlBtn}}" target="_blank" role="button" class="btn btn-outline-secondary ">
                                        <i class="fas fa-link"></i></a>
                                @endif
                        </div>
                        @endif

                        @if($isRepeatable || $isSortable ||$urlBtn )
                    </div>
                @endif
                @include('laravel-form-builder::help_block')
                @include('laravel-form-builder::errors')

                @if (\Illuminate\Support\Arr::has($options,'datalist') )
                    {{--Support DataList--}}
                    <datalist id="{{$name.'_datalist'}}">
                            @foreach (\Illuminate\Support\Arr::get($options,'datalist',[]) as $option)
                            <option value="{{$option}}">
                        @endforeach
                        </datalist>
                @endif
            @endif



            @if ($showLabel && $showField)
                @if ($options['wrapper'] !== false)
    </div>
    @endif
@endif

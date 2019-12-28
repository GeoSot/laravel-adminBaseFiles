<!--FileField   -->
@if($showLabel && $showField)
    @php
        $wrapAttrs=  $options['wrapperAttrs'];
        $x=preg_match('/class="(.*?)"/', $wrapAttrs,$classContents );
        $withoutClass= preg_replace('/class=".*?"/', '', $wrapAttrs);
         $existsClass=($options['value']) ?' fileinput-exists ' :' fileinput-new ';
        $viewAndRemoveOnly=\Illuminate\Support\Arr::get($options, 'viewAndRemoveOnly', false) ;

    @endphp
    @if ($showLabel && $options['label'] !== false && $options['label_show'])
        {!! Form::label($name, $options['label'], $options['label_attr']) !!}
    @endif

    <div class="{!! $classContents[1] !!} fileinput  {!! $existsClass !!}" data-toggle="fileInput" {!! $withoutClass !!} >


        @if ($showField)

            <div class="input-group mb-3 ">
                @if(!$viewAndRemoveOnly)
                    <span data-trigger="fileinput"
                          class="fileinput-filename  form-control text-truncate  ">@if($options['value']){!! is_string($options['value'])?$options['value']:$options['value']->first()->full_name !!}@endif</span>
                    {!!  Form::input($type, $name,null, array_merge( ['hidden'=>true, ] ))  !!}
                    <input type="hidden" name="remove_{{$name}}" data-id="{{\Illuminate\Support\Arr::get($options, 'id')}}">
                @else
                    <span class="form-control text-truncat  ">@if(\Illuminate\Support\Arr::has($options,'model')){{ $options['model']->name }}@endif</span>
                @endif
                <input type="hidden" name="old_{{$name}}" value="{{\Illuminate\Support\Arr::get($options, 'id')}}">
                <span class="hidden fileinput-invalidMsg  text-truncate" hidden> @lang($packageVariables->get('nameSpace').'admin/generic.button.wrongFile',['types'=>'']) </span>
                <div class="input-group-append">
                    @if(!$viewAndRemoveOnly)
                        <button class="btn btn-secondary fileinput-exists " type="button"
                                data-dismiss="fileinput"> @lang($packageVariables->get('nameSpace').'admin/generic.button.remove')</button>
                        <button class="btn btn-secondary " type="button" data-trigger="fileinput">
                            <span class="fileinput-new"> @lang($packageVariables->get('nameSpace').'admin/generic.button.selectFile')</span>
                            <span class="fileinput-exists"> @lang($packageVariables->get('nameSpace').'admin/generic.button.change')</span>
                        </button>
                    @else
                        @if($val=$options['value'])
                            @php($href=
                                      $options['model'] instanceOf \App\Models\MediaModels\FileModel
                                      ?route($options['model']->getFrontEndConfigPrefixed('admin', 'route').'.edit', $options['model'])
                                      :$val)
                            <a class=" btn btn-secondary btn-sm align-middle" role="button" href="{{$href}}" target="_blank"><i class="fa fa-eye"></i></a>
                        @endif
                        @if(\Illuminate\Support\Arr::get($options, 'repeatable', false) ?? $viewAndRemoveOnly)
                            <button class="btn btn-danger ml-auto btn-sm" type="button" data-remove="fileinput"><i class="fa fa-minus"></i></button>
                        @endif
                </div>
            </div>
            @include('laravel-form-builder::help_block')
        @endif

        @include('laravel-form-builder::errors')


    </div>

@endif

<!--FileField  END -->

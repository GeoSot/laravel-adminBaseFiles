<!--ImageField   -->
@if($showLabel && $showField)
    @php
        $wrapAttrs=  $options['wrapperAttrs'];
        $x=preg_match('/class="(.*?)"/', $wrapAttrs,$classContents );
        $withoutClass= preg_replace('/class=".*?"/', '', $wrapAttrs);
         $existsClass=($options['value']) ?' fileinput-exists ' :' fileinput-new ';

        $imgWrapper=$options['img_wrapper']??[];

        $img=$options['img']??[];
        $imgClass= Arr::get($options,'img.class','');

        $imgWrapperAttrs = '';
        foreach (Arr::except($imgWrapper,['class']) as $key => $value) {
            $imgWrapperAttrs .= $key.'="'.$value .'"';
        }

    @endphp
    @if ($showLabel && $options['label'] !== false && $options['label_show'])
        {!! Form::label($name, $options['label'], $options['label_attr']) !!}
    @endif

    <div class="{!! $classContents[1] !!} fileinput  {!! $existsClass !!}" data-toggle="imageInput" {!! $withoutClass !!}>


        <div class="w-100 p-2 mb-1 shadow-sm d-flex mouse-pointer" data-trigger="fileinput" style="min-height:100px;">
            <div class="fileinput-preview  border border-light thumbnail  bg-light   {!! $imgWrapper['class'] ??'' !!}"
                 {!! $imgWrapperAttrs !!} data-imgclass="img-fluid  {!!$imgClass!!}">
                @php
                    $value='';
                    $optionsValue=$options['value'];
                     if((is_string($optionsValue) and $optionsValue)){
                         $value=$optionsValue;
                     }
                     if( $optionsValue instanceof  \Illuminate\Support\Collection and $optionsValue->count()){
                         $value=$optionsValue->first()->getUrl();
                     }
                     if( $optionsValue instanceof  \App\Models\Media\Medium && in_array($optionsValue->aggregate_type,[\App\Models\Media\Medium::TYPE_IMAGE,\App\Models\Media\Medium::TYPE_IMAGE_VECTOR])){
                         $value=$optionsValue->getUrl();
                     }
                @endphp
                @if ($value)
                    <img src="{!! \GeoSot\BaseAdmin\App\Models\Media\Medium::getDummyImageUrl()!!}" data-src="{!! $value !!}" class="lazyload img-fluid   {!!$imgClass!!}"/>
                @endif
            </div>

        </div>


        @if ($showField)

            {!!  Form::input($type, $name,null, array_merge( $options['attr'] ,['hidden'=>true, 'accept'=>"image/*"] ))  !!}

            <input type="hidden" name="remove_{{$name}}" data-id="{{\Illuminate\Support\Arr::get($options, 'id')}}">
            <input type="hidden" name="old_{{$name}}" value="{{\Illuminate\Support\Arr::get($options, 'id')}}">
            <span class="hidden fileinput-invalidMsg" hidden> @lang($packageVariables->get('nameSpace').'admin/generic.button.wrongFile',['types'=>'jpeg, jpg, png, gif']) </span>
            <div class="buttons py-1 d-flex flex-wrap">
                <button class="btn btn-secondary btn-sm mb-1" type="button" data-trigger="fileinput">
                    <span class="fileinput-new"> @lang($packageVariables->get('nameSpace').'admin/generic.button.selectImage')</span>
                    <span class="fileinput-exists"> @lang($packageVariables->get('nameSpace').'admin/generic.button.change')</span>
                </button>
                <button class="btn btn-secondary  btn-sm fileinput-exists mx-1 mb-1" type="button" data-dismiss="fileinput">
                    @lang($packageVariables->get('nameSpace').'admin/generic.button.remove')
                </button>

            @if($val=$options['value'])
                    @php($href=
                              $options['value'] instanceOf \App\Models\Media\Medium
                               ?$options['value']->frontConfigs->getRoute('edit')
                              :$val)
                    <a class=" btn btn-secondary btn-sm align-middle mb-1" role="button" href="{{$href}}" target="_blank"><i class="fa fa-eye"></i></a>
                @endif
                @if(\Illuminate\Support\Arr::get($options, 'repeatable', false))
                    <button class="btn btn-danger ml-auto btn-sm mb-1" type="button" data-remove="fileinput"><i class="fa fa-minus"></i></button>
                @endif
            </div>
            @include('laravel-form-builder::help_block')
        @endif

        @include('laravel-form-builder::errors')


    </div>

@endif

<!--ImageField  END -->

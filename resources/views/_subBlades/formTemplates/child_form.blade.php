@php
    $wrapAttrs=  $options['wrapperAttrs'];
     $x=preg_match('/class="(.*?)"/', $wrapAttrs,$classContents );
     $childModels=$options['data'];
     $withoutClass= preg_replace('/class=".*?"/', '', $wrapAttrs);

     $isRepeatable=\Illuminate\Support\Arr::get($options,'repeatable',false);
     $isSortable=\Illuminate\Support\Arr::get($options,'sortable',false);
     $viewAndRemoveOnly=\Illuminate\Support\Arr::get($options,'viewAndRemoveOnly',false);
     $parentForm=\Illuminate\Support\Arr::get($options,'form');
     $extraFields=['sortable'=>$isSortable,'repeatable'=>$isRepeatable, 'viewAndRemoveOnly'=>$viewAndRemoveOnly ];

@endphp
@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        <div class="{!! $classContents[1] !!}   @if( $isSortable ) sortableItem @endif" {!! $withoutClass !!} >
    @endif
@endif
        @if ($showLabel && $options['label'] !== false && $options['label_show'])
            {!!  Form::customLabel($name, $options['label'], $options['label_attr'])  !!}
        @endif
        @if($showField)
                @if($isSortable )
                    <span class="sortingHandler mouse-pointer px-2"><i class="fa fa-sort"></i></span>
                @endif
            @foreach ((array)$options['children'] as $child)
                @if( ! in_array( $child->getRealName(), (array)$options['exclude']) )
                    {!! $child->render() !!}
                @endif
            @endforeach

            @include('laravel-form-builder::help_block')

        @endif

        @include('laravel-form-builder::errors')
        @if($isRepeatable)
         <div class="text-right ">
                <span class=" text-danger mouse-pointer"  data-toggle="removeParent" data-target=".form-group"><i
                            class="fa fa-minus px-2 py-2"></i></span>
         </div>
        @endif

@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        </div>
    @endif
@endif

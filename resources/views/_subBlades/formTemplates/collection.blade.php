@if($showLabel && $showField)

    @if($options['wrapper'] !== false)
        <div {!! $options['wrapperAttrs'] !!} >
            @endif
            @endif

            @if($showLabel && $options['label'] !== false && $options['label_show'])
                <?= Form::customLabel($name, $options['label'], $options['label_attr']) ?>
            @endif
            @php
                $childModels=$options['data'];

                $isRepeatable=\Illuminate\Support\Arr::get($options,'repeatable',false);
                $isSortable=\Illuminate\Support\Arr::get($options,'sortable',false);
                $viewAndRemoveOnly=\Illuminate\Support\Arr::get($options,'viewAndRemoveOnly',false);
                $parentForm=\Illuminate\Support\Arr::get($options,'form');
                $extraFields=['sortable'=>$isSortable,'repeatable'=>$isRepeatable, 'viewAndRemoveOnly'=>$viewAndRemoveOnly ]

            @endphp


            @if($showField)
                <div class="js-collectionContainer">

                    <div class="js-collectionItems {!!\Illuminate\Support\Arr::get($options,'items_wrapper_class')  !!} @if($isSortable) sortableWrapper @endif">
                        @foreach ($children=(array)$options['children'] as $child)
                            @php
                                if($childModels){

                                      $model=  $childModels->where('id',$child->getOption('value'))->first();
                                       $extraFields=array_merge($extraFields,['model'=>$model]);
                                      if($model){
                                              $child->setOption('id',$child->getOption('value'));
                                              $child->setOption('value',$model->{$child->getOption('final_property')});
                                          }
                                    }
                                 $parentForm=$parentForm??$child->getParent()
                            @endphp
                            {!!  $child->render($extraFields) !!}
                        @endforeach
                    </div>
                    @if($isRepeatable)
                        <input type="hidden" name="repeatable_{{$options['real_name']}}" value="true">
                        <div class="text-right ">

                            @if($parentForm->{$options['real_name']}->prototype()->getType()==='file')
                                @include('baseAdmin::_subBlades.media.library.mediaLibrary',['inputName'=>"add_{$options['real_name']}",'multiple'=>true])
                            @endif
                            <button class="js-addToCollection btn btn-outline-success btn-sm  mb-1" role="button" type="button" data-initial-count="{{count($children)}}"
                                    data-prototype="{{$parentForm->{$options['real_name']}->prototype()->render($extraFields)}}">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    @endif
                </div>

                @include('laravel-form-builder::help_block')

            @endif

            @include('laravel-form-builder::errors')


            @if($showLabel && $showField)
                @if($options['wrapper'] !== false)
        </div>
    @endif
@endif

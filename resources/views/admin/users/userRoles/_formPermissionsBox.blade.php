@component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($packageVariables->get('nameSpace').$viewVals->get('modelLang').'.formTitles.permissions'),'viewVals'=>$viewVals] )
    {{--{!! form_row($form->permissions) !!}--}}
    @php($permissionsGrouped=$viewVals->get('extraValues')->get('permissionsGrouped'))
    @php($permFields=collect($form->permissions->getChildren())->groupBy([function ($item) {
               return \Illuminate\Support\Str::before($item->getOption('label'),'.');
           },                        function ($item) {
               return \Illuminate\Support\Str::after($item->getOption('label'),'-');
           }]))

    @foreach($permissionsGrouped as $groupName=>$group)
        <div class="row mb-3">
                     <div class="font-weight-bold col-12 bg-light form-group py-1">{{ucfirst($groupName)}}</div>
            @foreach($group as $subGroupName=>$subGroup)
                <div class=" form-group col-6 col-sm-4 col-lg-3 col-xl-2">
                             <div class="mb-2 text-break font-weight-bolder text-muted">
                                    @include($packageVariables->get('blades').'admin._includes._selectAllBtn',['id'=>$subGroupName,'target'=>$subGroupName,'title'=>$subGroupName])
                                </div>
                                 <div class="ml-4">

                             @foreach($permFields->get($groupName)->get($subGroupName) as $checkableType )
                                         @php($customLabelName=str_replace(['-'.$subGroupName,$groupName.'.'],'',$checkableType->getOption('label')))
                                         {!! $checkableType->render(['label'=>$customLabelName,  'attr' => ['data-select-all' => $subGroupName]]) !!}
                                     @endforeach
                                 </div>
                         </div>
            @endforeach

    </div>
    @endforeach



@endcomponent

@if ($viewVals->get('record') )
    <div class="{{$class??' col-12 mb-3'}}">
        @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('modelLang').'.formTitles.blocks')] )
            <ul class="list-unstyled">
                @foreach($viewVals->get('record')->blocks as $block)
                    <li>
                        <span>{!! $block->frontConfigs->getAdminLink('slug') !!}</span>
                        <span class="ml-2 small text-muted">( ID:{{$block->getKey()}} )</span>
                    </li>
                @endforeach
            </ul>
            @slot('footer')
                {!! (new \App\Models\Pages\PageBlock())->frontConfigs->getAdminLink(__($viewVals->get('modelLang').'.general.createNewBlock'),true,['class'=>'ml-auto btn btn-sm btn-link']) !!}
            @endslot
        @endcomponent
    </div>
@endif

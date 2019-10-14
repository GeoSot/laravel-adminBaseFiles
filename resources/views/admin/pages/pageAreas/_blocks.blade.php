@if ($viewVals->get('record') )
    <div class="{{$class??' col-12 mb-3'}}">
        @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('modelLang').'.formTitles.blocks')] )
            <ul class="list-unstyled">
                @foreach($viewVals->get('record')->blocks as $block)
                    <li>
                        <span> {!! $block->getDashBoardLink('slug') !!}</span><span class="ml-2 small text-muted">( ID:{{$block->getKey()}} )</span>
                    </li>
                @endforeach
            </ul>
        @endcomponent
    </div>
@endif

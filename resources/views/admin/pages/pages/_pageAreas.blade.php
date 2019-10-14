@if ($viewVals->get('record') )
    <div class="{{$class??' col-12 mb-3'}}">
        @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('modelLang').'.formTitles.contentAreas')] )
            @foreach($viewVals->get('record')->pageAreas as $pageArea)
                <ul class="list-unstyled">
                    <li>
                        <span> {!! $pageArea->getDashBoardLink('slug') !!}</span><span class="ml-2 small text-muted">( ID:{{$pageArea->getKey()}} )</span>
                    </li>
                    <ul>
                        @foreach($pageArea->blocks as $block)
                            <li>
                                <span> {!! $block->getDashBoardLink('slug') !!}</span><span class="ml-2 small text-muted">( ID:{{$block->getKey()}} )</span>
                            </li>
                        @endforeach
                    </ul>
                    @endforeach
                </ul>
                @endcomponent
    </div>
@endif

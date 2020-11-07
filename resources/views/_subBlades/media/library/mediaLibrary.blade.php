@component($packageVariables->get('blades').'_subBlades._components.modal',['id'=>'mediaLibraryModal','animation'=>'','dialogClass' =>'modal-xl vw-100'] )
    @slot('triggerBtn')
        <a href="#" data-toggle="modal" data-target="#mediaLibraryModal" role="button" class="btn btn-outline-primary btn-sm mb-1 ml-auto ">
            @lang("baseAdmin::admin/media/mediumGallery.modal.button")
        </a>
    @endslot

    @slot('title')
        @lang("baseAdmin::admin/media/mediumGallery.modal.title")
    @endslot
    @slot('footer')

        <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('.close')</button>
    @endslot

    <div class="js-uppy-dashboard-container"></div>
    <div></div>
@endcomponent


@extends($packageVariables->get('adminLayout'))
@component($packageVariables->get('blades').'admin._components.createEditButtons',['viewVals'=>$viewVals]    )

    @if($viewVals->get('action')=='edit' )
        @slot('after')
            <div class="ml-auto">
                @if(auth()->user()->canImpersonate() and $viewVals->get('record')->canBeImpersonated() and auth()->user()->id!=$viewVals->get('record')->id )
                    <a class="btn btn-outline-info  " href="{{ route('admin.impersonate', $viewVals->get('record')->id) }}">
                        <span class="btn-label text-danger"><i class="fa fa-user-o"></i></span>
                         @lang($packageVariables->get('nameSpace').$viewVals->get('modelLang').'.general.impersonate')
                    </a>
                @endif
                    @if($contact=$viewVals->get('record')->contact)
                        <a class="btn btn-outline-secondary  m-1" role="button"
                           href="{{route($contact->getFrontEndConfigPrefixed('admin', 'route') . '.edit', $contact)}}">
                            <span class="btn-label"><i class="fa fa-address-card" aria-hidden="true"></i></span>
                             @lang($packageVariables->get('nameSpace').$viewVals->get('modelLang').'.fields.contactAssigned')
                        </a>
                    @endif
            </div>
        @endslot
    @endif

@endcomponent

@section('content')
    @php($form=$viewVals->get('extraValues')->get('form'))
    {!!  form_start($form) !!}

    <div class="row">
    <div class="col-md-6 col-12 mb-3">
    @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($packageVariables->get('nameSpace').$viewVals->get('baseLang').'.formTitles.first')] )
            {!! form_until($form, 'bio') !!}
        @endcomponent
        </div>
    <div class="col-md-6 col-12 mb-3">
    @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($packageVariables->get('nameSpace').$viewVals->get('modelLang').'.formTitles.contactDetails')] )
            {!! form_until($form, 'phone2') !!}
        @endcomponent
        </div>
    <div class="col-md-6 col-12 mb-3">
    @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($packageVariables->get('nameSpace').$viewVals->get('baseLang').'.formTitles.advancedSettings')] )
            {!!form_until($form, 'roles'); !!}
        @endcomponent
        </div>
         <div class="col-md-6 col-12 mb-3">
    @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($packageVariables->get('nameSpace').$viewVals->get('baseLang').'.formTitles.third')] )
                 {!!form_rest($form); !!}
             @endcomponent
        </div>
</div>
    {!!form_end($form, true); !!}
@endsection

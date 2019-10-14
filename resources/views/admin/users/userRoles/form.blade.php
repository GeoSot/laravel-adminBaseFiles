@extends($packageVariables->get('adminLayout'))
@php
    use Illuminate\Support\Collection;
     /**
     * @var Collection $packageVariables
     * @var Collection $viewVals
     */
@endphp

@component($packageVariables->get('blades').'admin._components.createEditButtons',['viewVals'=>$viewVals]    )
@endcomponent
@section('content')
    @php($form=$viewVals->get('extraValues')->get('form'))
    {!!  form_start($form) !!}

    <div class="row">
    <div class="col-md-6 col-12 mb-3">
        @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.first')] )
            {!! form_until($form, 'is_protected') !!}
        @endcomponent
        </div>


        <div class="col-md-6 col-12 mb-3">
        @include($packageVariables->get('blades').'admin.users.userRoles._formUsersBox')
        </div>
       <div class=" col-12 mb-3">
           @include($packageVariables->get('blades').'admin.users.userRoles._formPermissionsBox')
       </div>
    </div>
    {!!form_end($form, true); !!}
@endsection

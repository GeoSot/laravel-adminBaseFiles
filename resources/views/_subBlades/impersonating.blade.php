@if(auth()->check() and auth()->user()->isImpersonated())
    <div class="my-3 alert {{$class??'alert-primary'}} shadow py-1 d-flex flex-wrap align-items-center justify-content-center" role="alert">
                        <div class="text px-3 font-weight-bold my-1">{{\App\Models\Users\User::find( app('impersonate')->getImpersonatorId())->full_name}}  @lang($packageVariables->get('nameSpace').'admin/generic.menu.impersonation.isImpersonating')
                            !!!</div>
                        <a class=" btn btn-success" href="{{ route('admin.impersonate.leave') }}"> @lang($packageVariables->get('nameSpace').'admin/generic.menu.impersonation.leave')</a>
                    </div>
@endif

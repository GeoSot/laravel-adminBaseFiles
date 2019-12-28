@extends($packageVariables->get('siteLayout'))


@section('content')
    <div class="bg-gray">
        <div class="loginForm-inner">
            <h3 class="heading mb-35"> @lang($packageVariables->get('nameSpace').'admin/generic.afterLoginPage.msg')</h3>
            <div class="d-flex justify-content-center">
                <a class="btn-info btn-lg btn mx-5"
                   href="{{ \App\Providers\RouteServiceProvider::HOME}}"> @lang($packageVariables->get('nameSpace').'admin/generic.afterLoginPage.btnSite')</a>
                <a class="btn-info btn-lg btn mx-5" href="{{route('admin.dashboard')}}"> @lang($packageVariables->get('nameSpace').'admin/generic.afterLoginPage.btnAdmin')</a>
            </div>
        </div>
    </div>
@endsection




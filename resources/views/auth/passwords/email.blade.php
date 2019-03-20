@extends($packageVariables->get('siteLayout'))

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class=" col col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4 h4 text-primary"> @lang($packageVariables->get('nameSpace').'auth.pages.forgotPassword.title')</div>
                        {!! form($form) !!}
                        <div class="text-right">
                            <a href="{{route('login')}}"> @lang($packageVariables->get('nameSpace').'auth.pages.forgotPassword.loginLink')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

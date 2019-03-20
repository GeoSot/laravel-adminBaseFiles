@extends($packageVariables->get('siteLayout'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
         <div class=" col col-md-8 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4 h4 text-primary">{{ __($packageVariables->get('nameSpace').'Verify Your Email Address') }}</div>
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __($packageVariables->get('nameSpace').'A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif
                    {{ __($packageVariables->get('nameSpace').'Before proceeding, please check your email for a verification link.') }}
                    {{ __($packageVariables->get('nameSpace').'If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __($packageVariables->get('nameSpace').'click here to request another') }}</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

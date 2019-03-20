@extends($packageVariables->get('siteLayout'))

@section('content')
    <div class="container">
          <div class="row justify-content-center">
            <div class=" col col-md-8 col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-4 h4 text-primary"> @lang($packageVariables->get('nameSpace').'auth.pages.resetPassword.title')</div>
                        {!! form($form) !!}
                        <div class="text-right">
                             <a href="{{route('password.request',['email'=>$form->getField('email')->getOption('value')])}}"> @lang($packageVariables->get('nameSpace').'auth.pages.resetPassword.resetPasswordLink')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

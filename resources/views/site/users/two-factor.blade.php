@extends($packageVariables->get('siteLayout'))
@php($record= $viewVals->get('record') )
@php($form= $viewVals->get('extraValues')->get('twoFactorsForm') )
@php($lang=$packageVariables->get('nameSpace').$viewVals->get('modelLang').'.twoFa.')
@section('documentTitle')
    @lang($packageVariables->get('nameSpace').$viewVals->get('modelLang').'.general.singular' ): {{$record->full_name}}
@endsection
{{--@php(  session()->forget('auth.password_confirmed_at'))--}}
@section('content')


<div id="twoFactorsAuth" class="col-12 col-md-6 col-lg-6 align-self-center">

    <h3 class="h3 text-muted border-bottom mt-5 mb-4">@lang($lang.'title')</h3>

    @if($record->two_factor_secret)

        @if (!$record->two_factor_confirmed_at)
            <div class="card">
                <div id="twoFactorsAuthConfirm" class="card-body">
                    {!! form(\Kris\LaravelFormBuilder\Facades\FormBuilder::create(\GeoSot\BaseAdmin\App\Forms\Auth\ConfirmUserTwoFactorAuthenticationForm::class,['model' => $record,])) !!}
                </div>
            </div>
        @else
            <div class="text-primary text-center h4 mb-4">@lang($lang.'enabled')</div>
        @endif



        @if (session('status') == 'two-factor-authentication-confirmed')

            <div class="card card-body my-2">
                <div class="text-success">@lang($lang.'confirmed')</div>
                <div class="mt-2">@lang($lang.'storeRecoveryCode')</div>
                <ul class="mt-2 text-left text-sm">

                    @foreach((array) $record->recoveryCodes() as $code)
                        <li class="mt-1">{{$code}}</li>
                    @endforeach
                </ul>

            </div>

        @endif

    @endif


    <div id="twoFactorsAuthToggle">
        {!! form($form) !!}
    </div>
</div>

@endsection

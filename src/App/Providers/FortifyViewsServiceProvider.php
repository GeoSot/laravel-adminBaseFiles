<?php

namespace GeoSot\BaseAdmin\App\Providers;

use GeoSot\BaseAdmin\App\Forms\Auth\ConfirmPasswordForm;
use GeoSot\BaseAdmin\App\Forms\Auth\ForgotPasswordForm;
use GeoSot\BaseAdmin\App\Forms\Auth\LoginForm;
use GeoSot\BaseAdmin\App\Forms\Auth\RegisterForm;
use GeoSot\BaseAdmin\App\Forms\Auth\ResetPasswordForm;
use GeoSot\BaseAdmin\App\Forms\Auth\TwoFactorsChallengeForm;
use Illuminate\Support\ServiceProvider;
use Kris\LaravelFormBuilder\FormBuilderTrait;
use Laravel\Fortify\Fortify;

class FortifyViewsServiceProvider extends ServiceProvider
{
    use FormBuilderTrait;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Fortify::loginView(function () {
            return view('baseAdmin::auth.login', ['form' => $this->form(LoginForm::class)]);
        });

        Fortify::registerView(function () {
            return view('baseAdmin::auth.register', ['form' => $this->form(RegisterForm::class)]);
        });
        Fortify::verifyEmailView('baseAdmin::auth.verify-email');

        Fortify::requestPasswordResetLinkView(function () {
            return view('baseAdmin::auth.forgot-password', ['form' => $this->form(ForgotPasswordForm::class)]);
        });
        Fortify::resetPasswordView(function () {
            return view('baseAdmin::auth.reset-password', ['form' => $this->form(ResetPasswordForm::class)]);
        });
        Fortify::confirmPasswordView(function () {
            return view('baseAdmin::auth.confirm-password', ['form' => $this->form(ConfirmPasswordForm::class)]);
        });

        Fortify::twoFactorChallengeView(function () {
            return view('baseAdmin::auth.two-factor-challenge', ['form' => $this->form(TwoFactorsChallengeForm::class)]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}

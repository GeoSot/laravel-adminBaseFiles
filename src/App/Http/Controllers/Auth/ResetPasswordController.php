<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use GeoSot\BaseAdmin\App\Traits\Controller\Auth\ResetPasswordFormTrait;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords, FormBuilderTrait;


    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  Request  $request
     * @param  string|null  $token
     *
     * @return Factory|View
     */
    public function showResetForm(Request $request, $token = null)
    {

        $form = $this->getForm($token, $request->input('email'));

        return view('auth.passwords.reset', compact('form', 'token', 'email'));
    }

    /**
     * @param  string  $token
     *
     * @param  string  $emailDefault
     * @return Form
     */
    public function getForm(string $token, string $emailDefault = '')
    {


        return $this->plain($this->getFormOptions())
            ->add('token', 'hidden', ['value' => $token])
            ->add('email', 'email', [
                'value' => $emailDefault,
                'attr' => ['autofocus' => true],
                'rules' => 'required|email',
            ])
            ->add('password', 'password', [
                'rules' => 'required|string|min:6|confirmed'
            ])
            ->add('password_confirmation', 'password', [
                'rules' => 'required'
            ])
            ->add('resetPass_btn', 'submit', [
                'wrapper' => ['class' => 'form-group text-center'],
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);

    }

    /**
     * @return array
     */
    protected function getFormOptions(): array
    {
        return [
            'method' => 'POST',
            'url' => route('password.request'),
            'language_name' => 'baseAdmin::auth.fields'
        ];
    }

}

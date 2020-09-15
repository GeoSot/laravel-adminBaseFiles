<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */


    use SendsPasswordResetEmails, FormBuilderTrait;

    /**
     * Display the form to request a password reset link.
     *
     * @return Response
     */
    public function showLinkRequestForm()
    {
        $form = $this->getForm();

        return view('baseAdmin::auth.passwords.email', compact('form'));
    }

    /**
     *
     * @return Form
     */
    public function getForm()
    {

        return $this->plain($this->getFormOptions())
            ->add('email', 'email', [
                'rules' => 'required|email',
                'value' => request()->input('email')
            ])
            ->add('forgotPass_btn', 'submit', [
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
            'url' => route('password.email'),
            'language_name' => 'baseAdmin::auth.fields'
        ];
    }
}

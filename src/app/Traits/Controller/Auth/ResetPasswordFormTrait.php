<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 26/1/2019
 * Time: 4:14 πμ
 */

namespace GeoSot\BaseAdmin\App\Traits\Controller\Auth;


use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilderTrait;

trait ResetPasswordFormTrait
{
    use FormBuilderTrait;

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


        $form = $this->plain($this->getFormOptions());
        $form->add('token', 'hidden', ['value' => $token]);
        $form->add('email', 'email', [
            'value' => $emailDefault,
            'attr' => ['autofocus' => true],
            'rules' => 'required|email',

        ]);
        $form->add('password', 'password', [
            'rules' => 'required|string|min:6|confirmed'
        ])->add('password_confirmation', 'password', ['rules' => 'required']);
        $form->add('resetPass_btn', 'submit', [
            'wrapper' => ['class' => 'form-group text-center'],
            'attr' => ['class' => 'btn btn-outline-primary'],
        ]);

        return $form;
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

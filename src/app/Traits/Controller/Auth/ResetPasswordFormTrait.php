<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 26/1/2019
 * Time: 4:14 πμ
 */

namespace GeoSot\BaseAdmin\App\Traits\Controller\Auth;


use Kris\LaravelFormBuilder\Facades\FormBuilder;

trait ResetPasswordFormTrait
{


    /**
     * @param string $token
     *
     * @return \Kris\LaravelFormBuilder\Form
     */
    public function getForm(string $token)
    {


        $form = FormBuilder::plain($this->getFormOptions());
        $form->add('token', 'hidden', ['value' => $token]);
        $form->add('email', 'email', [
            'value' => request()->input('email'),
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

<?php

namespace GeoSot\BaseAdmin\App\Forms\Auth;

class ResetPasswordForm extends AuthForm
{
    public function getFormFields()
    {
        $emailDefault = request()->input('email');
        $token = request()->input('token');
        $this
            ->add('token', 'hidden', ['value' => $token])
            ->add('email', 'email', [
                'value' => $emailDefault,
                'attr'  => ['autofocus' => true],
                'rules' => 'required|email',
            ])
            ->add('password', 'password', [
                'rules' => 'required|string|min:6|confirmed',
            ])
            ->add('password_confirmation', 'password', [
                'rules' => 'required',
            ]);

        $this->addSubmitBtn('resetPass_btn');
    }

    protected function actionUrl(): string
    {
        return route('password.update');
    }
}

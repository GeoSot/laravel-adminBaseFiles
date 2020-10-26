<?php

namespace GeoSot\BaseAdmin\App\Forms\Auth;

class ForgotPasswordForm extends AuthForm
{
    public function getFormFields()
    {
        $this->add('email', 'email', [
            'rules' => 'required|email',
            'value' => request()->input('email'),
        ]);
        $this->addSubmitBtn('forgotPass_btn');
    }

    protected function actionUrl(): string
    {
        return route('password.email');
    }
}

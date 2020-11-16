<?php

namespace GeoSot\BaseAdmin\App\Forms\Auth;


class LoginForm extends AuthForm
{

    public function getFormFields()
    {
        $this
            ->add('email', 'email', [
                'rules' => 'required|email',
            ])
            ->add('password', 'password', [
                'rules' => 'required|string'
            ])
            ->add('remember', 'checkbox');

        $this->addSubmitBtn('login_btn');


    }

    protected function actionUrl(): string
    {
        return route('login');
    }


}

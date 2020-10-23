<?php

namespace GeoSot\BaseAdmin\App\Forms\Auth;

class RegisterForm extends AuthForm
{


    public function getFormFields()
    {

        $this
            ->add('first_name', 'text', [
                'rules' => 'required|string|max:255'
            ])
            ->add('last_name', 'text', [
                'rules' => 'required|string|max:255'
            ])
            ->add('email', 'email', [
                'rules' => 'required|string|email|max:255|unique:users',
            ])
            ->add('password', 'password', [
                'rules' => 'required|string|min:6|confirmed'
            ])
            ->add('password_confirmation', 'password');

        $this->addSubmitBtn('register_btn');
    }

    protected function actionUrl(): string
    {
        return route('register');
    }

}

<?php

namespace GeoSot\BaseAdmin\App\Forms\Auth;


class ConfirmPasswordForm extends AuthForm
{

    public function getFormFields()
    {

        $this->add('password', 'password', [
            'rules' => 'required',
        ]);
        $this->addSubmitBtn('confirm_btn');

    }


    protected function actionUrl(): string
    {
        return route('password.confirm');
    }


}

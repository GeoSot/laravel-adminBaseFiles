<?php

namespace GeoSot\BaseAdmin\App\Forms\Auth;

class TwoFactorsChallengeForm extends AuthForm
{

    public function getFormFields()
    {

        $this
            ->add('code', 'number', [
                'rules' => 'required',
                'attr' => ['autocomplete' => 'one-time-code'],
            ])->add('recovery_code', 'number', [
                'rules' => 'required',
                'attr' => ['autocomplete' => 'one-time-code'],
            ]);
        $this->addSubmitBtn('login_btn');


    }


    protected function actionUrl(): string
    {
        return '/two-factor-challenge';
    }


}

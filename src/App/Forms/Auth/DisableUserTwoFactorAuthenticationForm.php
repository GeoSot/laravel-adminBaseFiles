<?php

namespace GeoSot\BaseAdmin\App\Forms\Auth;

use GeoSot\BaseAdmin\App\Forms\Site\BaseFrontForm;
use GeoSot\BaseAdmin\App\Forms\Site\UserUpdatePasswordForm as InitialForm;

class DisableUserTwoFactorAuthenticationForm extends BaseFrontForm
{

    public function getFormFields()
    {
        $this->setFormOptions($this->getThisFormOptions());
        $this->add('disable', 'submit', [
            'attr' => ['class' => 'btn btn-outline-danger mt-3'],
            'wrapper' => ['class' => 'form-group text-center'],
        ]);
    }


    protected function getThisFormOptions(): array
    {
        return [
            'method' => 'DELETE',
            'language_name' => 'baseAdmin::site/users/user.twoFa',
            'url' => route('two-factor.disable'),
        ];
    }

}


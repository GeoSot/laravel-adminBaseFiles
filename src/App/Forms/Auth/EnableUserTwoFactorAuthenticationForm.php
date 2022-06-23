<?php

namespace GeoSot\BaseAdmin\App\Forms\Auth;

use GeoSot\BaseAdmin\App\Forms\Site\BaseFrontForm;
use GeoSot\BaseAdmin\App\Forms\Site\UserUpdatePasswordForm as InitialForm;

class EnableUserTwoFactorAuthenticationForm extends BaseFrontForm
{



    public function getFormFields()
    {
        $this->setFormOptions($this->getThisFormOptions());
        $this->add('enable', 'submit', [
            'attr' => ['class' => 'btn btn-outline-success mt-3'],
            'wrapper' => ['class' => 'form-group text-center'],
        ]);
    }


    protected function getThisFormOptions(): array
    {
        return [
            'method' => 'POST',
            'language_name' => 'baseAdmin::site/users/user.twoFa',
            'url' => route('two-factor.enable'),
        ];
    }

}


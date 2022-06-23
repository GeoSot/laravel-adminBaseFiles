<?php

namespace GeoSot\BaseAdmin\App\Forms\Auth;

use GeoSot\BaseAdmin\App\Forms\Site\BaseFrontForm;

class TwoFactorsChallengeForm extends BaseFrontForm
{

    public function getFormFields()
    {
        $this->setFormOptions($this->getThisFormOptions());

        $this->add('code', 'text', [
            'attr' => ['autocomplete' => 'one-time-code'],
            'wrapper' => ['data-js-code' => 'default'],
        ]);

        $this->add('recovery_code', 'text', [
            'attr' => ['autocomplete' => 'one-time-code'],
            'wrapper' => ['hidden' => true, 'data-js-code' => 'recovery'],
        ]);

        $this->add('submit', 'submit', [
            'attr' => ['class' => 'btn btn-outline-success mt-3'],
            'wrapper' => ['class' => 'form-group text-center'],
        ]);

    }


    protected function getThisFormOptions(): array
    {
        return [
            'method' => 'POST',
            'language_name' => 'baseAdmin::site/users/user.twoFa.challenge',
            'url' => route('two-factor.login'),
        ];
    }

}

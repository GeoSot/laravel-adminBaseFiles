<?php

namespace GeoSot\BaseAdmin\App\Forms\Auth;

use GeoSot\BaseAdmin\App\Forms\Site\BaseFrontForm;

class ConfirmPasswordForm extends BaseFrontForm
{

    public function getFormFields()
    {
        $this->setFormOptions($this->getThisFormOptions());

        $this
            ->add('password', 'password', [
                'rules' => 'required',
            ])
            ->add('confirm_btn', 'submit', [
                'wrapper' => ['class' => 'form-group text-center'],
                'attr' => ['class' => 'btn btn-outline-primary'],
            ]);

    }

    /**
     * @return array
     */
    protected function getThisFormOptions(): array
    {
        return [
            'method' => 'POST',
            'url' => route('password.confirm'),
            'language_name' => 'baseAdmin::auth.fields'
        ];
    }


}

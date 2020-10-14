<?php

namespace GeoSot\BaseAdmin\App\Forms\Auth;

use GeoSot\BaseAdmin\App\Forms\Site\BaseFrontForm;

class ForgotPasswordForm extends BaseFrontForm
{

    public function getFormFields()
    {
        $this->setFormOptions($this->getThisFormOptions());

        $this
            ->add('email', 'email', [
                'rules' => 'required|email',
                'value' => request()->input('email')
            ])
            ->add('forgotPass_btn', 'submit', [
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
            'url' => route('password.email'),
            'language_name' => 'baseAdmin::auth.fields'
        ];
    }


}

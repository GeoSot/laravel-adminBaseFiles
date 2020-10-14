<?php

namespace GeoSot\BaseAdmin\App\Forms\Auth;


use GeoSot\BaseAdmin\App\Forms\Site\BaseFrontForm;

class ResetPasswordForm extends BaseFrontForm
{


    public function getFormFields()
    {

        $emailDefault=request()->input('email');
        $token=request()->input('token');
        return $this->setFormOptions($this->getThisFormOptions())
            ->add('token', 'hidden', ['value' => $token])
            ->add('email', 'email', [
                'value' => $emailDefault,
                'attr' => ['autofocus' => true],
                'rules' => 'required|email',
            ])
            ->add('password', 'password', [
                'rules' => 'required|string|min:6|confirmed'
            ])
            ->add('password_confirmation', 'password', [
                'rules' => 'required'
            ])
            ->add('resetPass_btn', 'submit', [
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
            'url' => route('password.update'),
            'language_name' => 'baseAdmin::auth.fields'
        ];
    }

}

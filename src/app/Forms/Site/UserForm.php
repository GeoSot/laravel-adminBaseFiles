<?php

namespace GeoSot\BaseAdmin\App\Forms\Site;
use \Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class UserForm extends BaseFrontForm
{
    public function getFormFields()
    {
        $this->addStaticTextTitle($this->transText('personalData'), 'h3 text-muted border-bottom mt-5 mb-4');
        $this->add('first_name', 'text')->add('last_name', 'text')->add('email', 'email');
        $this->add('preferred_lang', 'select', [
            'choices' => array_map(function ($el) {
                return $el['native'];
            },  LaravelLocalization::getSupportedLocales()),
            'empty_value' => $this->getSelectEmptyValueLabel(),
        ]);
        $this->addStaticTextTitle($this->transText('changePassword'), 'h3 text-muted border-bottom mt-5 mb-4');
        $this->add('current_password', 'password');
        $this->add('password', 'password', [
            'value' => false,
        ])->add('password_confirmation', 'password');


        $this->add('submit', 'submit', [
            'attr' => ['class' => 'btn btn-outline-primary mt-3'],
            'wrapper' => ['class' => 'form-group text-center'],
        ]);

    }
}

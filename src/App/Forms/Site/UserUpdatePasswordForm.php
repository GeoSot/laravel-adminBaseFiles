<?php

namespace GeoSot\BaseAdmin\App\Forms\Site;

class UserUpdatePasswordForm extends BaseFrontForm
{
    public function getFormFields()
    {
        $this->setFormOptions($this->getThisFormOptions());
        $this->addStaticTextTitle($this->transText('changePassword'), 'h3 text-muted border-bottom mt-5 mb-4');
        $this->add('current_password', 'password');
        $this->add('password', 'password', [
            'value' => false,
        ])->add('password_confirmation', 'password');

        $this->add('submit', 'submit', [
            'attr'    => ['class' => 'btn btn-outline-primary mt-3'],
            'wrapper' => ['class' => 'form-group text-center'],
        ]);
    }

    protected function getThisFormOptions(): array
    {
        return [
            'method' => 'PUT',
            'url'    => route('user-password.update'),
        ];
    }
}

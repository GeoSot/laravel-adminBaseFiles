<?php

namespace GeoSot\BaseAdmin\App\Forms\Auth;

use GeoSot\BaseAdmin\App\Forms\Site\BaseFrontForm;

abstract class AuthForm extends BaseFrontForm
{

    protected function initializeValues(): void
    {
        parent::initializeValues();
        $this->setFormOptions($this->getThisFormOptions());
    }

    public function addSubmitBtn(string $name)
    {
        $this->add($name, 'submit', [
            'wrapper' => ['class' => 'form-group text-center'],
            'attr' => ['class' => 'btn btn-outline-primary'],
        ]);
    }

    abstract protected function actionUrl(): string;

    /**
     * @return array
     */
    protected function getThisFormOptions(): array
    {
        return [
            'method' => 'POST',
            'url' => $this->actionUrl(),
            'language_name' => 'baseAdmin::auth.fields'
        ];
    }

}

<?php

namespace GeoSot\BaseAdmin\App\Forms\Auth;

use GeoSot\BaseAdmin\App\Forms\Site\BaseFrontForm;
use Illuminate\Support\Str;

class ConfirmUserTwoFactorAuthenticationForm extends BaseFrontForm
{

    public function getFormFields()
    {
        $this->setFormOptions($this->getThisFormOptions());
        $this->addStaticTextTitle($this->transText('confirmCTA'), 'font-weight-bold text-primary');
        $this->add('scanQR', 'static', [
            'tag' => 'div',
            'attr' => ['class' => 'text-center'],
            'value' => $this->getModel()->twoFactorQrCodeSvg()]);
        $this->add('setupKey', 'static', [
            'value' => decrypt($this->getModel()->two_factor_secret),
            'label_attr' => ['class' => 'my-0 small']
        ]);

        $this->add('code');
        $this->add('confirm2FaSubmit', 'submit', [
            'attr' => ['class' => 'btn btn-outline-primary mt-3'],
            'wrapper' => ['class' => 'form-group text-center'],
        ]);
    }


    protected function getThisFormOptions(): array
    {
        $this->setErrorBag('confirmTwoFactorAuthentication');
        return [
            'method' => 'POST',
            'language_name' => 'baseAdmin::site/users/user.twoFa',
            'url' => route('two-factor.confirm')
        ];
    }

}


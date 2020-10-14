<?php

namespace GeoSot\BaseAdmin\App\Forms\Auth;

use GeoSot\BaseAdmin\App\Forms\Site\BaseFrontForm;
use Illuminate\Http\Request;


class LoginForm extends BaseFrontForm
{

    public function getFormFields()
    {
        $this->setFormOptions($this->getThisFormOptions());
        $this
            ->add('email', 'email', [
                'rules' => 'required|email',
            ])
            ->add('password', 'password', [
                'rules' => 'required|string'
            ])
            ->add('remember', 'checkbox')
            ->add('login_btn', 'submit', [
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
            'url' => route('login'),
            'language_name' => 'baseAdmin::auth.fields'
        ];
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     *
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->isAbleTo('admin.*')) {
            return redirect()->route('admin.dashboard');
        }
        return null;
    }


}

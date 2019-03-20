<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 26/1/2019
 * Time: 4:14 πμ
 */

namespace GeoSot\BaseAdmin\App\Traits\Controller\Auth;


use Kris\LaravelFormBuilder\Facades\FormBuilder;

trait ForgotPasswordFormTrait
{


    /**
     *
     * @return \Kris\LaravelFormBuilder\Form
     */
    public function getForm()
    {

        $form = FormBuilder::plain($this->getFormOptions())->add('email', 'email', [
            'rules' => 'required|email',
            'value' => request()->input('email')
        ])->add('forgotPass_btn', 'submit', [
            'wrapper' => ['class' => 'form-group text-center'],
            'attr' => ['class' => 'btn btn-outline-primary'],
        ]);

        return $form;
    }

    /**
     * @return array
     */
    protected function getFormOptions(): array
    {
        return [
            'method' => 'POST',
            'url' => route('password.email'),
            'language_name' => 'baseAdmin::auth.fields'
        ];
    }
}

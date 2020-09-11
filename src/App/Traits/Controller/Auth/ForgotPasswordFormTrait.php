<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 26/1/2019
 * Time: 4:14 πμ
 */

namespace GeoSot\BaseAdmin\App\Traits\Controller\Auth;


use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilderTrait;

trait ForgotPasswordFormTrait
{
    use FormBuilderTrait;

    /**
     * Display the form to request a password reset link.
     *
     * @return Response
     */
    public function showLinkRequestForm()
    {
        $form = $this->getForm();

        return view('baseAdmin::auth.passwords.email', compact('form'));
    }

    /**
     *
     * @return Form
     */
    public function getForm()
    {

        $form = $this->plain($this->getFormOptions())->add('email', 'email', [
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

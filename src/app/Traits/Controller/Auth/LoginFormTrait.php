<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 26/1/2019
 * Time: 4:14 πμ
 */


namespace GeoSot\BaseAdmin\App\Traits\Controller\Auth;


use Illuminate\Http\Response;
use Kris\LaravelFormBuilder\Facades\FormBuilder;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilderTrait;

trait LoginFormTrait
{
    use FormBuilderTrait;


    /**
     * Show the application's login form.
     *
     * @return Response
     */
    public function showLoginForm()
    {
        $form = $this->getForm();

        return view('baseAdmin::auth.login', compact('form'));
    }

    /**
     *
     * @return Form
     */
    public function getForm()
    {
        $form = FormBuilder::plain($this->getFormOptions());
        $form->add('email', 'email', [
            'rules' => 'required|email',
        ]);
        $form->add('password', 'password', [
            'rules' => 'required|string'
        ]);
        $form->add('remember', 'checkbox');
        $form->add('login_btn', 'submit', [
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
            'url' => route('login'),
            'language_name' => 'baseAdmin::auth.fields'
        ];
    }
}

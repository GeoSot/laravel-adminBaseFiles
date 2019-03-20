<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 26/1/2019
 * Time: 4:14 πμ
 */

namespace GeoSot\BaseAdmin\App\Traits\Controller\Auth;


use Kris\LaravelFormBuilder\Facades\FormBuilder;

trait RegisterFormTrait
{

    /**
     * Can override  protected function showRegistrationForm() with this
     *    return view('auth.register', compact('form'));
     *
     * @return \Kris\LaravelFormBuilder\Form
     */
    public function getForm()
    {


        $form = FormBuilder::plain($this->getFormOptions());
        $form->add('first_name', 'text', [
            'rules' => 'required|string|max:255'
        ]);
        $form->add('last_name', 'text', [
            'rules' => 'required|string|max:255'
        ]);
        $form->add('email', 'email', [
            'rules' => 'required|string|email|max:255|unique:users',
        ]);
        $form->add('password', 'password', [
            'rules' => 'required|string|min:6|confirmed'
        ]);
        $form->add('password_confirmation', 'password');
        $form->add('register_btn', 'submit', [
            'wrapper' => ['class' => 'form-group text-center'],
            'attr'    => ['class' => 'btn btn-outline-primary'],
        ]);

        return $form;
    }


    /**
     * Can override  protected function validator(array $data) with this
     *   return Validator::make($data, $this->>getFormValidationRules())
     * @return array
     */
    protected function getFormValidationRules()
    {
        $form = $this->getForm();
        $validationRules = [];
        foreach ($form->getFields() as $key => $field) {
            $fieldRules = collect($field->getValidationRules())->get('rules');
            if (is_array($fieldRules)) {
                $validationRules = array_merge($validationRules, $fieldRules);
            }
        }

        return $validationRules;
    }

    /**
     * @return array
     */
    protected function getFormOptions(): array
    {
        return [
            'method'        => 'POST',
            'url'           => route('register'),
            'language_name' => 'baseAdmin::auth.fields'
        ];
    }
}

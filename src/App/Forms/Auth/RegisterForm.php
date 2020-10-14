<?php

namespace GeoSot\BaseAdmin\App\Forms\Auth;

use GeoSot\BaseAdmin\App\Forms\Site\BaseFrontForm;

class RegisterForm extends BaseFrontForm
{


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


    public function getFormFields()
    {

        return $this->setFormOptions($this->getThisFormOptions())
            ->add('first_name', 'text', [
                'rules' => 'required|string|max:255'
            ])
            ->add('last_name', 'text', [
                'rules' => 'required|string|max:255'
            ])
            ->add('email', 'email', [
                'rules' => 'required|string|email|max:255|unique:users',
            ])
            ->add('password', 'password', [
                'rules' => 'required|string|min:6|confirmed'
            ])
            ->add('password_confirmation', 'password')
            ->add('register_btn', 'submit', [
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
            'url' => route('register'),
            'language_name' => 'baseAdmin::auth.fields'
        ];
    }


}

<?php
/**
 * Created by PhpStorm.
 * User: Sotis
 * Date: 26/1/2019
 * Time: 4:14 πμ
 */

namespace GeoSot\BaseAdmin\App\Traits\Controller\Auth;


use App\Models\Users\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilderTrait;

trait RegisterFormTrait
{
    use FormBuilderTrait;

    /**
     * Show the application registration form.
     *
     * @return Response
     */
    public function showRegistrationForm()
    {
        $form = $this->getForm();

        return view('auth.register', compact('form'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, $this->getFormValidationRules());
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
     * Can override  protected function showRegistrationForm() with this
     *    return view('auth.register', compact('form'));
     *
     * @return Form
     */
    public function getForm()
    {


        $form = $this->plain($this->getFormOptions());
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
            'url' => route('register'),
            'language_name' => 'baseAdmin::auth.fields'
        ];
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        return config('baseAdmin.config.models.user')::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}

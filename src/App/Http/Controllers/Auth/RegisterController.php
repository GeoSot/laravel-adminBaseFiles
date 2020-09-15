<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use RegistersUsers, FormBuilderTrait;


    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


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

        return $this->plain($this->getFormOptions())
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
     * @return \App\Models\Users\User
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

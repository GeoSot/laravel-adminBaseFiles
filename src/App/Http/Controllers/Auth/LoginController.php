<?php

namespace GeoSot\BaseAdmin\App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\Form;
use Kris\LaravelFormBuilder\FormBuilderTrait;

class LoginController extends Controller
{
    use AuthenticatesUsers, FormBuilderTrait;

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }

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
        return $this->plain($this->getFormOptions())
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
    protected function getFormOptions(): array
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

<?php


namespace GeoSot\BaseAdmin\App\Http\Controllers\Site;


use App\Models\Users\User;
use GeoSot\BaseAdmin\App\Forms\Site\UserProfileForm;
use GeoSot\BaseAdmin\App\Forms\Site\UserUpdatePasswordForm;
use Illuminate\Http\Response;

class UserProfileController extends BaseFrontController
{


    protected $_class = User::class;

    /**
     * Show the form for editing the specified resource.
     *
     *
     * @return Response
     */
    public function edit()
    {

        /** @var User $user */
        $user = auth()->user();
        $form = $this->makeForm(UserProfileForm::class, $user)->setErrorBag('updateProfileInformation');
        $form2 = $this->makeForm(UserUpdatePasswordForm::class, $user)->setErrorBag('updatePassword');
        $roles = $user->roles()->where('front_users_can_see', true)->get();

        $extraValues = collect(compact('form', 'form2', 'roles'));

        return view("baseAdmin::{$this->_modelsViewsDir}.edit", $this->variablesToView($extraValues, 'index', ['record' => auth()->user()]));

    }


}

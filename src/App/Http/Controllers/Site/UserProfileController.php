<?php


namespace GeoSot\BaseAdmin\App\Http\Controllers\Site;


use App\Models\Users\User;
use GeoSot\BaseAdmin\App\Forms\Site\UserProfileForm;
use GeoSot\BaseAdmin\App\Forms\Site\UserUpdatePasswordForm;
use GeoSot\BaseAdmin\App\Helpers\Models\FrontEndConfigs;
use GeoSot\BaseAdmin\Helpers\Base;
use Illuminate\Http\Response;
use Laravel\Fortify\Features;

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
        $form = null;
        $form2 = null;

        if (Features::enabled(Features::updateProfileInformation())) {
            $form = $this->makeForm(UserProfileForm::class, $user)->setErrorBag('updateProfileInformation');
        }
        if (Features::enabled(Features::updatePasswords())) {
            $form2 = $this->makeForm(UserUpdatePasswordForm::class, $user)->setErrorBag('updatePassword');
        }


        $roles = $user->roles()->where('front_users_can_see', true)->get();

        $extraValues = collect(compact('form', 'form2', 'roles'));

        return view($this->getViewFile(), $this->variablesToView($extraValues, 'index', ['record' => auth()->user()]));

    }

    /**
     * @return string
     */
    protected function getViewFile(): string
    {
        $side = FrontEndConfigs::SITE;
        return Base::addPackagePrefix("{$side}.users.edit");
    }


}

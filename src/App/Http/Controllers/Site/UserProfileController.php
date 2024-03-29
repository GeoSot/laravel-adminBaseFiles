<?php


namespace GeoSot\BaseAdmin\App\Http\Controllers\Site;


use App\Models\Users\User;
use Illuminate\Support\Collection;
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
        $profileForm = null;
        $passwordForm = null;

        if (Features::enabled(Features::updateProfileInformation())) {
            $profileForm = $this->makeForm(UserProfileForm::class, $user)->setErrorBag('updateProfileInformation');
        }
        if (Features::enabled(Features::updatePasswords())) {
            $passwordForm = $this->makeForm(UserUpdatePasswordForm::class, $user)->setErrorBag('updatePassword');
        }


        $roles = $user->roles()->where('front_users_can_see', true)->get();

        $extraValues = collect(compact('profileForm', 'passwordForm', 'roles'));

        return view($this->getViewFile(), $this->variablesToView($this->gatherExtraValues($extraValues), 'index', ['record' => $user]));

    }

    /**
     * @return string
     */
    protected function getViewFile(): string
    {
        $side = FrontEndConfigs::SITE;
        return Base::addPackagePrefix("{$side}.users.edit");
    }

    protected function gatherExtraValues(Collection $values): Collection
    {
        return $values;
    }
}

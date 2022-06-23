<?php


namespace GeoSot\BaseAdmin\App\Http\Controllers\Site;


use App\Models\Users\User;
use GeoSot\BaseAdmin\App\Forms\Auth\DisableUserTwoFactorAuthenticationForm;
use GeoSot\BaseAdmin\App\Forms\Auth\EnableUserTwoFactorAuthenticationForm;
use GeoSot\BaseAdmin\App\Forms\Auth\UserTwoFactorAuthenticationForm;
use Illuminate\Support\Collection;
use GeoSot\BaseAdmin\App\Helpers\Models\FrontEndConfigs;
use GeoSot\BaseAdmin\Helpers\Base;
use Illuminate\Http\Response;
use Laravel\Fortify\Features;

class UserTwoFactorAuthenticationController extends BaseFrontController
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

        $twoFactorsForm = $this->getTwoFaForm($user);

        $extraValues = collect(compact('twoFactorsForm'));

        return view($this->getViewFile(), $this->variablesToView($this->gatherExtraValues($extraValues), 'index', ['record' => $user]));

    }

    /**
     * @return string
     */
    protected function getViewFile(): string
    {
        $side = FrontEndConfigs::SITE;
        return Base::addPackagePrefix("{$side}.users.two-factor");
    }

    protected function gatherExtraValues(Collection $values): Collection
    {
        return $values;
    }


    public function getTwoFaForm(User $user): \Kris\LaravelFormBuilder\Form|\GeoSot\BaseAdmin\App\Forms\BaseForm
    {
        if ($user->two_factor_secret) {
            return $this->makeForm(DisableUserTwoFactorAuthenticationForm::class, $user);
        }
        return $this->makeForm(EnableUserTwoFactorAuthenticationForm::class, $user);
    }

}

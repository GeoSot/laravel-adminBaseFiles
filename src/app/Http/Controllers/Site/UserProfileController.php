<?php


namespace GeoSot\BaseAdmin\App\Http\Controllers\Site;


use App\Models\Users\User;
use GeoSot\BaseAdmin\App\Http\Controllers\BaseFrontController;
use Illuminate\Http\Request;
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
        $form = $this->makeForm('User', auth()->user());
        $extraValues = collect(['form' => $form]);

        return view("baseAdmin::{$this->_modelsViewsDir}.edit", $this->variablesToView($extraValues, 'index', ['record' => auth()->user()]));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = $user->rules();

        if (!is_null($request->input('password'))) {
            $rules = array_merge($rules, [
                'current_password' => 'required|samePassword:'.$user->getAuthPassword(),
            ]);
        }
        $request->validate($rules, $this->getModelValidationMessages());

        $user->update($request->all());


        flashMessage($this->getLang('profileChange.success.msg'));

        return redirect()->back();
    }


}

<?php


namespace GeoSot\BaseAdmin\App\Http\Controllers\Site;


use App\Models\Users\User;
use GeoSot\BaseAdmin\App\Http\Controllers\BaseFrontController;
use Illuminate\Http\Request;

class UserProfileController extends BaseFrontController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    public function __construct()
    {
        parent::__construct();

        $this->_class = User::class;
        $this->initializeModelValues();

    }

    /**
     * Show the form for editing the specified resource.
     *
     *
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $rules = $user->rules();

        if (!is_null($request->input('password'))) {
            $rules = array_merge($rules, [
                'current_password' => 'required|samePassword:' . $user->getAuthPassword(),
            ]);
        }
        $request->validate($rules, $this->getModelValidationMessages());

        $user->update($request->all());


        flashMessage($this->getLang('profileChange.success.msg'));

        return redirect()->back();
    }


}

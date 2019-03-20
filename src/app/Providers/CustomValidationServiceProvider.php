<?php

namespace GeoSot\BaseAdmin\App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class CustomValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $method_names = preg_grep('/^customRule_/', get_class_methods($this));
        foreach ($method_names as $class) {
            $this->$class();
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        //
    }

    /**
     * Checks if A richTextEditor Field is empty After Strips its html
     */
    public function customRule_requiredRichTextArea()
    {
        $name = 'requiredRichTextArea';
        Validator::extend($name, function ($attribute, $value, $parameters, $validator) {
            $cleanValue = strip_tags($value);

            return !(is_null($cleanValue) or empty($cleanValue));
        });

        Validator::replacer($name, function ($message, $attribute, $rule, $parameters) {
            return trans('validation.required', ['attribute' => $attribute]);
        });
    }

    /**
     *
     * Example    'current_password' => 'required|samePassword:' . $user->getAuthPassword(),
     */
    public function customRule_samePassword()
    {
        $name = 'samePassword';
        Validator::extend($name, function ($attribute, $value, $parameters, $validator) {
            return Hash::check($value, Arr::get($parameters, 0));
        });

        Validator::replacer($name, function ($message, $attribute, $rule, $parameters) {
            return trans('validation.samePassword', ['attribute' => $attribute]);
        });
    }
//Rule::unique('category_translations', 'slug')
//->where(function ($query) {
////    $query->where('locale', 'ru');
////})
//return [
//'name' => ['required', 'filled', 'max:255', 'min:2',
//Rule::unique('organization_translations', 'name')
//->whereNot('organization_id', $this->organization->id)
//]

}

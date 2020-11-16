<?php

namespace GeoSot\BaseAdmin\App\Providers;

use Collective\Html\FormBuilder;
use Form;
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


    public static function registerFormCustomRawLabel(): void
    {
        /* @var FormBuilder $form */
        $form = app('form');
        $form->macro('customLabel', function ($name, $value, $options = []) use ($form) {
            $escape = !Arr::get($options, 'raw', true);
            if (isset($options['for']) && $for = $options['for']) {
                unset($options['for']);
                return $form->label($for, $value, $options, $escape);
            }
            return $form->label($name, $value, $options, $escape);
        });
    }

}

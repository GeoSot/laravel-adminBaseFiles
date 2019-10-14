<?php
return [
    'defaults' => [
        'wrapper_class' => 'form-group',
        'wrapper_error_class' => 'has-error ',
        'label_class' => 'control-label',
        'field_class' => 'form-control',
        'field_error_class' => 'is-invalid',
        'help_block_class' => 'help-block small form-text text-muted',
        'error_class' => 'd-block  invalid-feedback',
        'required_class' => 'required',

        'checkbox' => [
            'wrapper_class' => 'custom-control custom-checkbox form-group',
            'label_class' => 'custom-control-label',
            'field_class' => 'custom-control-input',
        ],
        'static' => [
            'field_class' => 'form-control-plaintext',
        ],

        'textarea' => [
            'field_class' => 'form-control withEditor',
        ],

        // Override a class from a field.
        //'text'                => [
        //    'wrapper_class'   => 'form-field-text',
        //    'label_class'     => 'form-field-text-label',
        //    'field_class'     => 'form-field-text-field',
        //]
        //'radio'               => [
        //    'choice_options'  => [
        //        'wrapper'     => ['class' => 'form-radio'],
        //        'label'       => ['class' => 'form-radio-label'],
        //        'field'       => ['class' => 'form-radio-field'],
        //],
    ],


    // Templates
    'form' => 'laravel-form-builder::form',
    // 'text'            => 'laravel-form-builder::text',
    'text' => 'baseAdmin::_subBlades.formTemplates.text',
    'textarea' => 'laravel-form-builder::textarea',
    'button' => 'laravel-form-builder::button',
    'buttongroup' => 'laravel-form-builder::buttongroup',
    'radio' => 'laravel-form-builder::radio',
    'checkbox' => 'baseAdmin::_subBlades.formTemplates.checkbox',
    //    'checkbox'        => 'laravel-form-builder::checkbox',
    'select' => 'laravel-form-builder::select',
    'choice' => 'laravel-form-builder::choice',
    'repeated' => 'laravel-form-builder::repeated',
    'child_form' => 'baseAdmin::_subBlades.formTemplates.child_form',
    //    'child_form'      => 'laravel-form-builder::child_form',
    'collection' => 'baseAdmin::_subBlades.formTemplates.collection',
    //    'collection'      => 'laravel-form-builder::collection',
    'static' => 'baseAdmin::_subBlades.formTemplates.static',
    // 'static'          => 'laravel-form-builder::static',

    // Remove the laravel-form-builder:: prefix above when using template_prefix
    'template_prefix' => '',

    'default_namespace' => '',

    'custom_fields' => [//'datetime' => App\Forms\Fields\Datetime::class
    ]
];

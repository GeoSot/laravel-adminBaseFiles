<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin\Users;


use App\Models\Users\UserRole;
use GeoSot\BaseAdmin\App\Forms\Admin\BaseAdminForm;

class UserForm extends BaseAdminForm
{
    protected $formOptions = [];

    public function getFormFields()
    {


        $this->add('first_name', 'text')->add('last_name', 'text')->add('email', 'email')->add('password', 'password', [
            'value' => false,
        ])->add('password_confirmation', 'password')->add('gender', 'choice', [
            'choices' => [
                'M' => trans($this->languageName . '.gender_M'),
                'F' => trans($this->languageName . '.gender_F'),
            ],
            'choice_options' => [
                'wrapper' => ['class' => 'ml-3 custom-control custom-radio custom-control-inline'],
                'label_attr' => ['class' => 'custom-control-label'],
                'attr' => ['class' => 'custom-control-input'],
            ],
            //  'selected' => $this->model->fields
            'expanded' => true,
            'multiple' => false
        ]);
        $this->add('preferred_lang', 'select', [
            'choices' => array_map(function ($el) {
                return $el['native'];
            }, config('laravellocalization.supportedLocales')),
            'empty_value' => $this->getSelectEmptyValueLabel(),
        ]);
        $this->add('dob', 'text', [
            'template' => 'baseAdmin::_subBlades.formTemplates.dateTime',
            'cast' => ['php' => 'd/m/Y', 'js' => 'DD/MM/YYYY']
        ])->add('images', 'collection', [
            'type' => 'file',
            // 'repeatable' => true,
            //   'viewAndRemoveOnly'=>true,
            'options' => [
                'img_wrapper' => ['class' => 'mbed-responsive mbed-responsive-21by9 w-50   m-auto'],
                'img' => ['class' => ' mbed-responsive-item'],
                'label' => false,
                'template' => 'baseAdmin::_subBlades.formTemplates.image',
                'final_property' => 'file_path'
            ]
        ])->add('bio', 'textarea', ['attr' => ['rows' => '5']]);


        $this->add('notification_types', 'choice', [
            'choices' => ['mail' => 'Email', 'slack' => 'Slack'],
            'expanded' => true,
            'multiple' => true,
            'choice_options' => [
                'wrapper' => ['class' => 'ml-3 custom-control custom-checkbox custom-control-inline'],
                'label_attr' => ['class' => 'custom-control-label'],
                'attr' => ['class' => 'custom-control-input'],
            ],
            'selected' => ['mail'],
        ]);


        $this->add('slack_webhook_url', 'text');
        $this->addSeparatorLine();

        $this->add('address', 'text')->add('city', 'text')->add('postal', 'text')->add('state', 'text')->add('country', 'text')->add('phone1', 'text')->add('phone2', 'text');
        $this->addCheckBox('enabled');
        $this->add('roles', 'entity', [
            'class' => UserRole::class,
            'property' => 'display_name',
            'multiple' => true,
            'label' => $this->transText('roles.name')
            //                    'query_builder' => function (App\Language $lang) {
            //                        // If query builder option is not provided, all data is fetched
            //                        return $lang->where('active', 1);
            //                    }
        ]);
        $this->add('notes', 'textarea', ['attr' => ['rows' => '3'],]);

    }
}

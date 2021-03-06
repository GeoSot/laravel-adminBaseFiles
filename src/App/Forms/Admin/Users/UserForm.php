<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin\Users;


use App\Models\Media\Medium;
use App\Models\Users\UserRole;
use GeoSot\BaseAdmin\App\Forms\Admin\BaseAdminForm;
use GeoSot\BaseAdmin\Helpers\Base;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Fortify\Features;

class UserForm extends BaseAdminForm
{

    public function getFormFields()
    {
        $this->getMainFields();
        $this->getSecondaryFields();
        $this->getNotificationFields();
        $this->addSeparatorLine();
        $this->getAddressFields();
        $this->getThirdPanel();

    }

    private function getAddressFields(): void
    {
        $this->add('address', 'text')
            ->add('city', 'text')
            ->add('postal', 'text')
            ->add('state', 'text')
            ->add('country', 'text')
            ->add('phone1', 'text')
            ->add('phone2', 'text');
    }

    private function getMainFields(): void
    {
        $this->add('first_name', 'text')
            ->add('last_name', 'text')
            ->add('email', 'email')
            ->add('password', 'password', [
                'value' => false,
            ])
            ->add('password_confirmation', 'password');
    }

    protected function getThirdPanel(): void
    {
        $this->addCheckBox('is_enabled');

        if (!$this->isCreate && $this->getModel() instanceof MustVerifyEmail && Features::enabled(Features::emailVerification())) {
            $val = $this->getModel()->hasVerifiedEmail() ? 'fa-check text-success' : 'fa-times text-danger';
            $this->add('is_verified', 'static', [
                'value' => ':<span class="ml-2 fas ' . $val . '"> </span>',
                'attr' => ['class' => 'd-inline']
            ]);
        }

        $this->add('roles', 'entity', [
            'class' => UserRole::class,
            'property' => 'display_name',
            'multiple' => true,
            'label' => $this->transText('roles.name'),
            'query_builder' => function (UserRole $role) {
                return $role;
//                return $role->where($this->getModel()->getKeyName(), '<>', optional($this->getModel())->getKey());
//
//                // If query builder option is not provided, all data is fetched
//                return $lang->where('active', 1);
            }
        ]);


//        $this->add('rolesTeams', 'entity', [
//            'class' => UserRole::class,
//            'property' => 'display_name',
//            'multiple' => true,
//            'label' => $this->transText('roles.name')
//            //                    'query_builder' => function (App\Language $lang) {
//            //                        // If query builder option is not provided, all data is fetched
//            //                        return $lang->where('active', 1);
//            //                    }
//        ]);
//        $this->add('notes', 'textarea', ['attr' => ['rows' => '3'],]);
    }

    protected function getSecondaryFields(): void
    {
        $this->add('gender', 'choice', [
            'choices' => [
                'M' => $this->transText('gender_M'),
                'F' => $this->transText('gender_F'),
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

        if (Base::isMultiLingual()) {
            $this->add('preferred_lang', 'select', [
                'choices' => array_map(function ($el) {
                    return $el['native'];
                }, config('laravellocalization.supportedLocales')),
                'empty_value' => $this->getSelectEmptyValueLabel(),
            ]);
        }

        $this->add('dob', 'text', [
            'template' => 'baseAdmin::_subBlades.formTemplates.dateTime',
            'cast' => ['php' => 'd/m/Y', 'js' => 'DD/MM/YYYY']
        ]);
        $this->add(Medium::REQUEST_FIELD_NAME__IMAGE, 'collection', [
            'type' => 'file',
            // 'repeatable' => true,
            //   'viewAndRemoveOnly'=>true,
            'options' => [
                'img_wrapper' => ['class' => 'mbed-responsive mbed-responsive-21by9 w-50   m-auto'],
                'img' => ['class' => ' mbed-responsive-item'],
                'label' => false,
                'template' => 'baseAdmin::_subBlades.formTemplates.image',
                'final_property' => 'url'
            ]
        ]);
        $this->add('bio', 'textarea', ['attr' => ['rows' => '5']]);
    }

    protected function getNotificationFields(): void
    {
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
    }
}

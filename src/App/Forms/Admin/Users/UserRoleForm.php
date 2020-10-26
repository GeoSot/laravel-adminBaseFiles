<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin\Users;

use GeoSot\BaseAdmin\App\Forms\Admin\BaseAdminForm;
use GeoSot\BaseAdmin\App\Models\Users\UserPermission;

class UserRoleForm extends BaseAdminForm
{
    //    protected $formOptions = [
    //        'id' => 'mainForm',
    //    ];
    //
    public function getFormFields()
    {
        $modelInstance = $this->getModel();

        $this->add('display_name', 'text')->add('name', 'text');

        if ($modelInstance->id) {
            $this->modify('name', 'text', [
                'attr' => ['readonly' => true],
            ]);
        }
        $this->add('description', 'textarea');

        $this->addCheckBox('front_users_can_see');

        $this->addCheckBox('front_users_can_choose');
        $this->addCheckBox('is_protected');

        $this->add('permissions', 'entity', [
            'choices'        => UserPermission::all()->pluck('name', 'id')->toArray(),
            'choice_options' => [
                'wrapper'       => ['class' => 'custom-control custom-checkbox'],
                'label_class'   => 'custom-control-label',
                'field_class'   => 'custom-control-input',
                'includeHidden' => true,
            ],
            'selected' => $modelInstance->permissions->pluck('id')->toArray(),
            'expanded' => true,
            'multiple' => true,
            'label'    => false,
        ]);
    }
}

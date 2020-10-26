<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin\Users;

use GeoSot\BaseAdmin\App\Forms\Admin\BaseAdminForm;

class UserTeamForm extends BaseAdminForm
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
    }
}

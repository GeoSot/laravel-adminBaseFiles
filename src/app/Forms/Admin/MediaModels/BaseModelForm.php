<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin\MediaModels;


use GeoSot\BaseAdmin\App\Forms\Admin\BaseAdminForm;

class BaseModelForm extends BaseAdminForm
{

    public function getFormFields()
    {
        $this->addCheckBox('enabled');
        $this->add('order', 'number');


//        'attr' => [
//        'class' => 'form-control withOutEditor', 'rows' => '5'
//    ]

        $this->add('title', 'text');
        $this->add('description', 'textarea');
        $this->add('alt_attribute', 'text');
        $this->add('keywords', 'text');


    }
}

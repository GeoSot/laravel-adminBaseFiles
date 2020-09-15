<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin\Media;


use GeoSot\BaseAdmin\App\Forms\Admin\BaseAdminForm;

class MediumForm extends BaseAdminForm
{

    public function getFormFields()
    {
        $this->addCheckBox('enabled');
        $this->add('order', 'number');

        $this->add('title', 'text');
        $this->add('description', 'textarea');
        $this->add('alt_attribute', 'text');
        $this->add('keywords', 'text');


    }
}

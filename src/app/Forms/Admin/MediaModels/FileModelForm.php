<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin\MediaModels;


use GeoSot\BaseAdmin\App\Forms\Admin\BaseAdminForm;

class FileModelForm extends BaseAdminForm
{

    public function getFormFields()
    {
        $this->compose(BaseModelForm::class, ['language_name' => $this->getLanguageName()]);


    }
}

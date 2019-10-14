<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin\MediaModels;


use GeoSot\BaseAdmin\App\Forms\Admin\BaseAdminForm;

class ImageModelForm extends BaseAdminForm
{

    public function getFormFields()
    {
        $this->compose(BaseModelForm::class);
//        $this->compose(BaseModelForm::class, ['language_name' => $this->getLanguageName()]);


    }
}

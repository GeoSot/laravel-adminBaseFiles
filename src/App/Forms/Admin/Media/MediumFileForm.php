<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin\Media;


use GeoSot\BaseAdmin\App\Forms\Admin\BaseAdminForm;

class MediumFileForm extends BaseAdminForm
{

    public function getFormFields()
    {
        $this->compose(BaseMediaModelForm::class, ['language_name' => $this->getLanguageName()]);


    }
}

<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin\Media;


use GeoSot\BaseAdmin\App\Forms\Admin\BaseAdminForm;

class MediumGalleryForm extends BaseAdminForm
{

    public function getFormFields()
    {
        $this->compose(BaseMediaModelForm::class);
//        $this->compose(BaseModelForm::class, ['language_name' => $this->getLanguageName()]);


    }
}

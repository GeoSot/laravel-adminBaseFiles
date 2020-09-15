<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin\Media;


use GeoSot\BaseAdmin\App\Forms\Admin\BaseAdminForm;

class MediumGalleryForm extends BaseAdminForm
{

    public function getFormFields()
    {

        $this->addCheckBox('enabled');
        $this->add('title', 'text');
        $this->add('notes', 'textarea');
        $this->addSlug();

//        $this->add('related', 'textarea');


        $this->add('images', 'collection', [
            'type' => 'file',
            'repeatable' => true,
            //   'viewAndRemoveOnly'=>true,
            'options' => [
                'img_wrapper' => ['class' => 'mbed-responsive mbed-responsive-21by9 w-50   m-auto'],
                'img' => ['class' => ' mbed-responsive-item'],
                'label' => false,
                'template' => 'baseAdmin::_subBlades.formTemplates.image',
                'final_property' => 'url'
            ]
        ]);


    }
}

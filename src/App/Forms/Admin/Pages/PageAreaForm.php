<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin\Pages;


use GeoSot\BaseAdmin\App\Forms\Admin\BaseAdminForm;
use GeoSot\BaseAdmin\App\Models\Pages\Page;

class PageAreaForm extends BaseAdminForm
{
    //    protected $formOptions = [
    //        'id' => 'mainForm',
    //    ];
    //
    public function getFormFields()
    {

        $this->addCheckBox('enabled');
        $this->add('title', 'text');
        $this->add('sub_title', 'text');
        $this->add('slug', 'text');
        $this->add('page_id', 'entity', [
            'class' => Page::class,
            'property' => 'slug',
            'label' => $this->transText('parentPage'),
            'empty_value' => $this->getSelectEmptyValueLabel(),
        ]);


        $this->add('order', 'number');
        $this->add('css_class', 'text');
        $this->add('background_color', 'text', [
            'template' => 'baseAdmin::_subBlades.formTemplates.colorPicker',
        ]);
        $this->add('images', 'collection', [
            'type' => 'file',
            // 'repeatable' => true,
            //   'viewAndRemoveOnly'=>true,
            'options' => [
                'img_wrapper' => ['class' => 'mbed-responsive mbed-responsive-21by9 w-50   m-auto'],
                'img' => ['class' => ' mbed-responsive-item'],
                'label' => false,
                'template' => 'baseAdmin::_subBlades.formTemplates.image',
                'final_property' => 'url',
            ],
        ]);


//        $this->add('fields', 'collection', [
//            'type'       => 'form',
//            'options'    => [    // these are options for a single type
//                                 'class'         => 'Admin\Projects\SimpleFieldForm',
//                                 'label'         => false,
//                                 'wrapper'       => ['class' => 'row form-group'],
//                                 'language_name' => str_replace('fields', 'newFieldsLabels', $this->getLanguageName()),
//            ],
//            'label'      => false,
//            'repeatable' => true,
//            //'multiple'   => true,
//            //'empty_row'         => false,
//            'sortable'   => true,
//            'data'       => collect(optional($this->getModel())->pageBlocks)
//        ]);

        $this->add('notes', 'textarea', ['attr' => ['rows' => '3']]);


    }
}

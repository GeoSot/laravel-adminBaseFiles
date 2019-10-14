<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin\Pages;


use GeoSot\BaseAdmin\App\Forms\Admin\BaseAdminForm;
use GeoSot\BaseAdmin\App\Models\Pages\Page;

class PageForm extends BaseAdminForm
{

    public function getFormFields()
    {
        $this->addCheckBox('enabled');
        $this->add('title', 'text');
//        $this->add('sub_title', 'text');
        $this->add('parent_id', 'entity', [
            'class' => Page::class,
            'property' => 'slug',
            'label' => $this->transText('parentPage.title'),
            'empty_value' => $this->getSelectEmptyValueLabel(),
            'query_builder' => function (Page $page) {
                return $page->where($this->getModel()->getKeyName(), '<>', optional($this->getModel())->getKey());
            }
        ]);
        $this->add('slug', 'text', [
            'help_block' => [
                'text' => $this->transHelpText('slug')
            ]
        ]);
        if (!$this->isCreate) {
            $this->modify('slug', 'text', ['attr' => ['readonly' => 'readonly']]);
        }


        $this->add('meta_title', 'text');
        $this->add('meta_description', 'textarea', [
            'attr' => [
                'class' => 'form-control withOutEditor', 'rows' => '3'
            ],
            'help_block' => [
                'text' => $this->transHelpText('meta_description')
            ]
        ]);

        $this->add('keywords', 'text', [
            'help_block' => ['text' => $this->transHelpText('keywords')]
        ]);
        $this->add('meta_tags', 'textarea', [
            'attr' => ['class' => 'form-control withOutEditor', 'rows' => '3'],
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
                'final_property' => 'file_path',
            ],
        ]);


        $this->add('css', 'textarea', [
            'attr' => ['class' => 'form-control withOutEditor', 'rows' => '3']
        ]);
        $this->add('javascript', 'textarea', [
            'attr' => ['class' => 'form-control withOutEditor', 'rows' => '3']
        ]);

        $this->add('notes', 'textarea', ['attr' => ['rows' => '3']]);

    }
}

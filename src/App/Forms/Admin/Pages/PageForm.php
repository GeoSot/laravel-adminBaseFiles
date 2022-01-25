<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin\Pages;


use App\Models\Media\Medium;
use GeoSot\BaseAdmin\App\Forms\Admin\BaseAdminForm;
use GeoSot\BaseAdmin\App\Models\Pages\Page;
use Kris\LaravelFormBuilder\Field;

class PageForm extends BaseAdminForm
{

    public function getFormFields()
    {
        $this->addCheckBox('is_enabled');
        $this->add('title', 'text');
        $this->add('sub_title', 'text');
        $this->add('parent_id', 'entity', [
            'class' => Page::class,
            'property' => 'slug',
            'label' => $this->transText('parentPage.title'),
            'empty_value' => $this->getSelectEmptyValueLabel(),
            'query_builder' => function (Page $page) {
                return $page->where($this->getModel()->getKeyName(), '<>', optional($this->getModel())->getKey());
            },
        ]);
        $this->add('slug', 'text', [
            'help_block' => [
                'text' => $this->transHelpText('slug'),
            ],
        ]);
        if (!$this->isCreate) {
            $this->modify('slug', 'text', ['attr' => ['readonly' => 'readonly']]);
        }


        $this->add('meta_title', 'text');
        $this->add('meta_description', 'textarea', [
            'attr' => [
                'class' => 'form-control withOutEditor', 'rows' => '3',
            ],
            'help_block' => [
                'text' => $this->transHelpText('meta_description'),
            ],
        ]);

        $this->add('keywords', 'text', [
            'help_block' => ['text' => $this->transHelpText('keywords')],
        ]);
        $this->addExtraMetaTags();

        $this->add(Medium::REQUEST_FIELD_NAME__IMAGE, 'collection', [
            'type' => 'file',
//             'repeatable' => true,
            //   'viewAndRemoveOnly'=>true,
            'options' => [
                'img_wrapper' => ['class' => 'mbed-responsive mbed-responsive-21by9 w-50   m-auto'],
                'img' => ['class' => ' mbed-responsive-item'],
                'label' => false,
                'template' => 'baseAdmin::_subBlades.formTemplates.image',
                'final_property' => 'url',
            ],
        ]);


        $this->add('css', 'textarea', [
            'attr' => ['class' => 'form-control withOutEditor', 'rows' => '3'],
        ]);
        $this->add('javascript', 'textarea', [
            'attr' => ['class' => 'form-control withOutEditor', 'rows' => '3'],
        ]);

        $this->add('notes', 'textarea', ['attr' => ['rows' => '3']]);

    }

    protected function addExtraMetaTags()
    {
//        $this->add('meta_tags', 'textarea', [
//            'attr' => ['class' => 'form-control withOutEditor', 'rows' => '3'],
//        ]);
//        return;
        $subForm = $this->formBuilder->plain();
        $subForm->add('key', Field::TEXT, [
//            'label' => false,
//            'value'=>function($x){dump(8);},
            'wrapper' => [
                'class' => 'col-4',
            ],
        ]);
        $subForm->add('val', Field::TEXT, [
//            'label' => false,
//            'data'=>fn($x)=>dd($x),
//            'value'=>fn($x)=>dump($x),
            'wrapper' => [
                'class' => 'col',
            ],
        ]);
//        dd($this->getModel()->meta_tags);
//        dd($this->getModel()->getAttributes(),$this->getModel()->meta_tags,$this->getModel()->title);
//dd($this->getModel());
//        dd($this->getModel()->meta_tags);
//        dd($this->getModel()->meta_tags?:[]);
        $this->add('meta_tags', 'collection', [
            'type' => Field::FORM,
            'repeatable' => true,
            'multiple' => true,
//            'form' => $this,
//'value'=>fn($x)=>dd($x),
//            'data'=>function($x){dd($x);},
            'options' => [    // these are options for a single type
//                'is_child' => true,
                'class' => $subForm,
//                'value'=>fn($x)=>dump($x),
                'label' => false,
                'wrapper' => [
                    'class' => 'row',
                ],
            ],
        ]);
    }
}

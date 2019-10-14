<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin\Pages;


use GeoSot\BaseAdmin\App\Forms\Admin\BaseAdminForm;
use GeoSot\BaseAdmin\App\Models\Pages\PageArea;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class PageBlockForm extends BaseAdminForm
{
    //    protected $formOptions = [
    //        'id' => 'mainForm',
    //    ];
    //
    public function getFormFields()
    {
        $this->addCheckBox('enabled');
        $this->add('slug', 'text');
        if ($this->isCreate) {
            $this->addCheckBox('has_multiple_images');
            $this->add('layout', 'hidden');
        } else {


            list($viewsPath, $list) = $this->getLayoutsList();
            $this->add('layout', 'select', [
                'choices' => $list,
                'empty_value' => $this->getSelectEmptyValueLabel(),
                'help_block' => [
                    'text' => $this->transHelpText('layout', ['path' => $viewsPath])
                ]
            ]);

        }
        $this->add('page_area_id', 'entity', [
            'class' => PageArea::class,
            'property' => 'slug',
            'label' => $this->transText('pageArea.slug'),
            'empty_value' => $this->getSelectEmptyValueLabel(),

        ]);
        $this->add('order', 'number');
        $this->add('css_class', 'text');
        $this->add('background_color', 'text', [
            'template' => 'baseAdmin::_subBlades.formTemplates.colorPicker',
        ]);
        if (!$this->isCreate) {
            $this->getBlockMainContent();
        }

    }

    /**
     * @return array
     */
    protected function getLayoutsList(): array
    {
        $extension = ".blade.php";
        $viewsPath = "views/site/_includes/blockLayouts/".($this->getModel()->hasOneImage() ? 'simple/' : "multipleImages/");
        $layoutFiles = File::glob(resource_path($viewsPath."*".$extension));

        $list = [];
        foreach ($layoutFiles as $layoutFile) {
            $list[str_replace([resource_path('views'), $extension], '', $layoutFile)] = $this->getHumanTextForLayout($viewsPath, $extension, $layoutFile);
        }
        return [$viewsPath, $list];
    }

    /**
     * @param  string  $viewsPath
     * @param  string  $extension
     * @param $layoutFile
     * @return string
     */
    protected function getHumanTextForLayout(string $viewsPath, string $extension, $layoutFile)
    {
        $humanText = str_replace([resource_path($viewsPath), $extension], '', $layoutFile);

        return str_replace(['_', '-'], [' ', ' - '], ucfirst(Str::snake($humanText)));
    }

    protected function getBlockMainContent()
    {

        $this->add('title', 'text');
        $this->add('sub_title', 'text');
        $this->add('notes', 'textarea');
        $this->add('images', 'collection', [
            'type' => 'file',
            'multiple' => true,
            'repeatable' => !$this->getModel()->hasOneImage(),
            'items_wrapper_class' => 'row ',
            'options' => [
                'img_wrapper' => ['class' => 'embed-responsive embed-responsive-16by9   m-auto'],
                'img' => ['class' => ' embed-responsive-item'],
                'label' => false,
                'wrapper' => ['class' => $this->getModel()->hasOneImage() ? 'col-12 col-md-6 col-lg-4' : 'col-auto'.'  form-group'],
                'template' => 'baseAdmin::_subBlades.formTemplates.image',
                'final_property' => 'file_path',
                //                'data'    => array_merge($this->getFieldOptions(), $data),
            ],
        ]);
    }

}

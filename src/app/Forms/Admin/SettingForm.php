<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin;


use GeoSot\BaseAdmin\App\Forms\Admin\BaseAdminForm;
use Symfony\Component\Finder\Finder;

class SettingForm extends BaseAdminForm
{

    public function getFormFields()
    {

        if ($this->isCreate) {
            $this->getCreateFields();
        } else {
            $this->getEditFields();
            // $this->viewOptions['tabs']=
        }

    }

    protected function getCreateFields()
    {

        $this->add('key', 'text', ['attr' => ['list' => "keys"]]);
        $this->add('sub_group', 'text', ['attr' => ['list' => "subGroups"]]);
        $this->add('group', 'text', ['attr' => ['list' => "groups"]]);

        $this->add('settingsFieldsInfo', 'static', [
            'label' => false,
            'wrapper' => ['class' => 'form-group  my-4 px-3 py-2 small'],
            'value' => $this->transHelpText('settingsFieldsInfo') // If nothing is passed, data is pulled from model if any
        ]);


        $this->add('type', 'choice', [
            'choices' => collect($this->getModel()->choices)->mapWithKeys(function ($item) {
                return [$item => trans($this->languageName . '.types.' . $item)];
            })->toArray(),
            'empty_value' => $this->getSelectEmptyValueLabel(),
            'help_block' => [
                'text' => $this->transHelpText('type'),
            ],
        ]);
        $this->addSeparatorLine();
        $this->add('model_type', 'select', [
            'choices' => $this->getModelNames()->toArray(),
            'empty_value' => $this->getSelectEmptyValueLabel(),
            'help_block' => [
                'text' => $this->transHelpText('model_type')
            ]
        ]);
    }

    protected function getModelNames()
    {
        $modelPath = app_path('Models/');
        $files = Finder::create()->in($modelPath)->name('*.php')->contains('HasSettings')->sortByName();

        $modelsList = collect([]);

        collect($files)->each(function ($item) use ($modelsList, $modelPath) {
            $modelCleanName = str_replace('.php', '', $item->getFilename());
            $modelsList['App\\Models\\' . str_replace('/', '\\', $item->getRelativePath()) . '\\' . $modelCleanName] = $modelCleanName;
        });

        return $modelsList;
    }

    protected function getEditFields()
    {
        $this->getEditFirstFields();
        $this->getEditValueField();
        $this->getEditSecondFields();
        $this->getEditRelatedModelFields();
        $this->add('notes', 'textarea');

    }

    protected function getEditFirstFields()
    {

        $this->add('slug', 'text', [
            'attr' => ['readonly' => 'readonly'],
        ]);
        $this->add('type', 'text', ['attr' => ['readonly' => 'readonly']]);

    }

    protected function getEditValueField()
    {
        $type = $this->getModel()->type;


        if (in_array($type, ['textarea', 'number'])) {
            $this->add('value', $type);
        }

        if ($type == 'timeToMinutes') {
            $this->add('value', 'text', ['template' => 'baseAdmin::_subBlades.formTemplates.minutesToTime']);
        }
        if ($type == 'dateTime') {
            $this->add('value', 'text', [
                'template' => 'baseAdmin::_subBlades.formTemplates.dateTime',
                'cast' => ['php' => 'd/m/Y', 'js' => 'DD/MM/YYYY']
            ]);
        }

        if (in_array($type, ['collectionSting', 'collectionNumber'])) {
            $this->add('value', 'collection', [
                'type' => ('collectionSting' == $type) ? 'text' : 'number',
                'repeatable' => true,
                'options' => [    // these are options for a single type
                    'label' => false,
                ],
                'data' => collect(json_decode($this->getModel()->value))
            ]);
        }

        if (!$this->has('value')) {
            $this->add('value', 'text');
        }

    }

    protected function getEditSecondFields()
    {
        $this->add('key', 'text', [
            'attr' => ['readonly' => 'readonly'],
        ]);

        $this->add('sub_group', 'text', ['attr' => ['readonly' => 'readonly']]);

        $this->add('group', 'text', ['attr' => ['readonly' => 'readonly']]);
    }

    protected function getEditRelatedModelFields()
    {

        $relatedModelName = $this->getModel()->model_type;

        if ($relatedModelName) {
            //            $this->add('model_type', 'text', [
            //                'attr' => ['readonly' => 'readonly'],
            //            ]);

            $this->add('model_type', 'select', [
                'choices' => $this->getModelNames()->toArray(),
                'empty_value' => $this->getSelectEmptyValueLabel(),
                'attr' => ['disabled']
            ]);

            $this->add('model_id', 'entity', [
                'class' => $relatedModelName,
                'property' => 'title',
                'attr' => ['disabled'],
                'empty_value' => $this->getSelectEmptyValueLabel(),
                // 'query_builder' => function ( $el) {return $el->enabled();}
            ]);
        }


    }
}

<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin;


use App\Models\Media\Medium;
use Carbon\CarbonPeriod;
use GeoSot\BaseAdmin\Services\SettingsChoices;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Kris\LaravelFormBuilder\Field;
use Symfony\Component\Finder\Finder;

class SettingForm extends BaseAdminForm
{

    public function getFormFields()
    {

        $this->isCreate ? $this->getCreateFields() : $this->getEditFields();

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
            'choices' => Arr::sort(SettingsChoices::getSettingTypes()),
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
                'text' => $this->transHelpText('model_type'),
            ],
        ]);
    }

    protected function getModelNames(): Collection
    {
        $modelPath = app_path('Models/');
        $files = Finder::create()->in($modelPath)->name('*.php')->contains('HasSettings')->sortByName();

        $modelsList = collect([]);

        collect($files)->each(function ($item) use ($modelsList, $modelPath) {
            $modelCleanName = str_replace('.php', '', $item->getFilename());
            $modelsList['App\\Models\\'.str_replace('/', '\\', $item->getRelativePath()).'\\'.$modelCleanName] = $modelCleanName;
        });

        return $modelsList;
    }

    protected function getEditFields()
    {
        $this->getEditFirstFields();
        $this->getEditValueField();
        $this->add('dummy_hidden', Field::HIDDEN);
        $this->getEditSecondFields();
        $this->getEditRelatedModelFields();
        $this->add('notes', 'textarea');

    }

    protected function getEditFirstFields()
    {
        $attr = ['attr' => ['readonly' => 'readonly']];
        $this->add('slug', 'text', $attr);
        $this->add('type_to_human', 'text', $attr);
        $this->add('type', 'hidden');

    }

    protected function getEditValueField()
    {
        $type = $this->getModel()->type;

        if (in_array($type, ['textarea', 'number'])) {
            $this->add('value', $type);
            return;
        }

        if ($type == 'boolean') {
            $this->addCheckBox('value');
            return;
        }

        if ($type == 'timeToMinutes') {
            $this->add('value', 'text', ['template' => 'baseAdmin::_subBlades.formTemplates.minutesToTime']);
            return;
        }
        if ($type == 'dateTime') {
            $this->add('value', 'text', [
                'template' => 'baseAdmin::_subBlades.formTemplates.dateTime',
                'cast' => ['php' => 'd/m/Y', 'js' => 'DD/MM/YYYY'],
            ]);
            return;
        }


        if ($type == SettingsChoices::DATE_RANGE) {
            $value = $this->getModel()->value ? CarbonPeriod::createFromIso($this->getModel()->value) : null;

            $this->add('value', Field::HIDDEN);
            $this->add('value[from]', Field::DATETIME_LOCAL, [
                'value' => $value ? $value->start->toDateTimeLocalString() : null,
                'label' => $this->transText('date_range_from'),
            ]);
            $this->add('value[to]', Field::DATETIME_LOCAL, [
                'value' => $value ? $value->end->toDateTimeLocalString() : null,
                'label' => $this->transText('date_range_to'),
            ]);
            return;
        }

        $this->getValueField($type);

    }

    protected function getEditSecondFields()
    {
        $attr = ['attr' => ['readonly' => 'readonly']];
        $this->add('key', 'text', $attr);
        $this->add('sub_group', 'text', $attr);
        $this->add('group', 'text', $attr);
    }


    protected function getEditRelatedModelFields()
    {

        $relatedModelName = $this->getModel()->model_type;
        if ($relatedModelName) {

            $this->add('model_type', 'select', [
                'choices' => $this->getModelNames()->toArray(),
                'empty_value' => $this->getSelectEmptyValueLabel(),
                'attr' => ['disabled'],
            ]);

            $this->add('model_id', 'entity', [
                'class' => $relatedModelName,
                'property' => 'title',
                'attr' => ['disabled'],
                'empty_value' => $this->getSelectEmptyValueLabel(),
            ]);
        }


    }

    /**
     * @param $type
     */
    protected function getValueField($type): void
    {
        if (in_array($type, [SettingsChoices::COLLECTION_STRING, SettingsChoices::COLLECTION_NUMBER])) {
            $this->add('value', 'collection', [
                'type' => (SettingsChoices::COLLECTION_STRING == $type) ? 'text' : 'number',
                'repeatable' => true,
                'options' => [    // these are options for a single type
                    'label' => false,
                ],
                'data' => collect(json_decode($this->getModel()->value)),
            ]);
        }

        if (in_array($type, [Medium::TYPE_IMAGE, Medium::class])) {
            $this->add('value_dummy', 'collection', [
                'type' => 'file',
                // 'repeatable' => true,
                //   'viewAndRemoveOnly'=>true,
                'options' => [
                    'img_wrapper' => ['class' => 'mbed-responsive mbed-responsive-21by9 w-50   m-auto'],
                    'img' => ['class' => ' mbed-responsive-item'],
                    'label' => false,
                    'template' => 'baseAdmin::_subBlades.formTemplates.'.($type == Medium::TYPE_IMAGE ? 'image' : 'file'),
                    'final_property' => 'url',
                    'value' => function () {
                        $val = $this->getModel()->value;
                        return $val ? Medium::find($val) : '';
                    },
                ],
            ]);
            $this->add('value', 'hidden');
            return;
        }

        if (!$this->has('value')) {
            $this->add('value', 'text');
        }
    }
}

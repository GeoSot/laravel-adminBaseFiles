<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin;

use GeoSot\BaseAdmin\App\Forms\BaseForm;
use GeoSot\BaseAdmin\App\Traits\Eloquent\HasSettings;
use Illuminate\Support\Arr;
use Kris\LaravelFormBuilder\Fields\FormField;

abstract class BaseAdminForm extends BaseForm
{


    protected $addAfterSaveInput = true;


    public function buildForm()
    {
        parent::buildForm();
        if ($this->instanceIsModelClass($this->getModel())) {
            $this->createTranslatableFields();
            $this->getRulesAndCustomMessagesForFields($this->getModel());
            $this->includeModelRelatedSettings($this->getModel());
        }
    }


    /**
     * Handle the creation of translatable extra fields
     */
    protected function createTranslatableFields()
    {
        if (!config('baseAdmin.enable-TranslatableFields-OnModel', true)) {
            return;
        }
        $availableLocales = (array)config('translatable.locales', config('app.locale'));
        if (property_exists($this->getModel(), 'translatedAttributes')) {

            foreach (Arr::except($availableLocales, array_search(app()->getLocale(), $availableLocales)) as $locale) {
                foreach ($this->getModel()->translatedAttributes as $fieldName) {
                    $newName = "{$locale}[{$fieldName}]";
                    $field = $this->getField($fieldName);
                    $this->addAfter($fieldName, $newName, $field->getType(), $field->getOptions());
                    $newField = $this->getField($newName);
                    $this->addAttributesToTranslatableField($newField, $locale);
                }
            }
        }

    }

    /**
     * @param FormField $field
     * @param string    $locale
     */
    protected function addAttributesToTranslatableField(FormField $field, string $locale)
    {

        $field->setOption('label', $this->getTranslatableLabel($field, $locale));
        $field->setOption('attr', array_merge($field->getOption('attr'), [config('baseAdmin.translatables.input-locale-attribute', 'data-language') => $locale]));
        $field->setOption('wrapper.class', $field->getOption('wrapper.class') . '  ' . config('baseAdmin.translatables.form-group-class', 'form-group-translation '));
        $field->setOption('wrapper.group-translation', $locale);

    }

    /**
     * @param FormField $field
     * @param string    $locale
     *
     * @return mixed
     */
    protected function getTranslatableLabel(FormField $field, string $locale)
    {
        $labelLocaleIndicator = config('baseAdmin.translatables.label-locale-indicator', '<span>%label%</span> <span class="ml-2 badge badge-pill badge-light">%locale%</span>');
        $localizedLabel = str_replace('%label%', $field->getOption('label'), $labelLocaleIndicator);

        return str_replace('%locale%', $locale, $localizedLabel);

    }

    /**
     * @param $modelInstance
     */
    protected function getRulesAndCustomMessagesForFields($modelInstance): void
    {
        foreach ($this->getFields() as $field) {
            if (in_array($field->getName(), $modelInstance->getFillable())) {
                $field->setOption('rules', $modelInstance->getFieldRule($field->getName()));
                $field->setOption('error_messages', $modelInstance->getFieldErrorMessages($field->getName()));
            }
        }
    }

    /**
     * @param $modelInstance
     */
    public function includeModelRelatedSettings($modelInstance)
    {
        if (in_array('related_settings', array_keys($this->getFields())) or $this->isCreate or !in_array(HasSettings::class, class_uses($modelInstance))) {
            return;
        }
        $subForm = $this->formBuilder->plain();
        foreach ($modelInstance->settings as $setting) {

            $subForm->add($setting->key, 'static', [
                'label' => false,
                'value' => $setting->getDashBoardLink($setting->key, true, ['class' => 'mr-2 d-block']) . $setting->value_parsed_to_human,
            ]);
        }

        $this->add('related_settings', 'form', ['wrapper' => ['class' => ''], 'label' => false, 'class' => $subForm]);

    }


}

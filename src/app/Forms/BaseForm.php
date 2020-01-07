<?php

namespace GeoSot\BaseAdmin\App\Forms;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Kris\LaravelFormBuilder\Form;

abstract class BaseForm extends Form
{
    protected $addAfterSaveInput = false;

    protected $isCreate;

    /**
     *  HelpText Language Prefix
     */
    protected $helpTextLang;


    abstract public function getFormFields();

    public function buildForm()
    {

        $this->initializeValues();
        $this->getFormFields();


        if (!in_array('after_save', array_keys($this->getFields())) and $this->addAfterSaveInput) {
            $this->add('after_save', 'hidden');
        }
        //        if (!in_array('formClass', array_keys($this->getFields()))) {
        //            $this->add('formClass', 'hidden', ['value' => get_class($this)]);
        //        }
    }


    protected function initializeValues(): void
    {
        $this->helpTextLang = str_replace('fields', 'fieldsHelpTexts', $this->getLanguageName());

        $this->isCreate = !(optional($this->getModel())->id);
    }

    /**
     * @param $modelInstance
     *
     * @return bool
     */
    public function instanceIsModelClass($modelInstance): bool
    {
        return is_subclass_of($modelInstance, Model::class);
    }

    /**
     * @return array|string|null
     */
    public function getSelectEmptyValueLabel()
    {
        return __('baseAdmin::admin/generic.formTitles.formSelectPlaceHolder');
    }


    /**
     * Create translation text (modelLang.fields.).
     *
     * @param  string  $text
     *
     * @return  string
     */
    public function transText(string $text)
    {
        return __($this->getLanguageName().'.'.$text);
    }


    /**
     * Create translation Help Text (modelLang.fieldsHelpTexts.).
     *
     * @param  string  $text  *
     * @param  array  $replace
     *
     * @return  string
     */
    public function transHelpText(string $text, $replace = [])
    {
        return __($this->getHelpTextLanguageName().'.'.$text, $replace);
    }


    public function getHelpTextLanguageName()
    {
        return $this->helpTextLang;
    }

    /**
     * Create a new separator line.
     *
     * @param  string  $class
     * @param  array  $options
     *
     * @return  Form
     */
    public function addSeparatorLine($class = 'my-4 border-bottom border-primary', array $options = [])
    {
        $this->add(uniqid('separator_'), 'static', array_merge([
            'label' => false,
            'attr' => ['class' => $class]
        ], $options));

        return $this;
    }

    public function addSeparatorDoubleLine(array $options = [])
    {
        return $this->addSeparatorLine('mb-5 py-4 border-bottom border-primary', $options);
    }


    /**
     * @param  string  $text
     * @param  string  $addClass
     * @param  array  $options
     *
     * @return $this
     */
    public function addStaticTextTitle($text = '', $addClass = '', $options = [])
    {
        $this->add(Str::random(30), 'static', array_merge([
            'tag' => 'div',
            'label' => false,
            'attr' => ['class' => 'form-control-static '.$addClass], // This is the default
            'value' => $text
        ], $options));

        return $this;
    }


    /**
     * @param  string  $name
     * @param  array  $options
     * @return $this
     */
    public function addSlug(string $name = 'slug', $options = [])
    {
        $this->add($name, 'text', array_merge([
            'attr' => ['readonly' => $this->isCreate ? 'false' : 'readonly'],
        ], $options));
        return $this;
    }

    /**
     * @param  string  $name
     * @param  array  $options
     * @return $this
     */
    public function addCheckBox(string $name, $options = [])
    {
        $this->add($name, 'checkbox', array_merge([
            'template' => 'baseAdmin::_subBlades.formTemplates.checkbox',
            'includeHidden' => true,
        ], $options));

        return $this;
    }
}

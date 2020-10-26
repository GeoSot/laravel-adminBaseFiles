<?php

namespace GeoSot\BaseAdmin\App\Traits\Eloquent;

use Illuminate\Support\Arr;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Spatie\Translatable\HasTranslations;

trait HasRulesOnModel
{
    protected $rules = [];
    protected $errorMessages = [];

    /**
     * @param array $mergeRules
     *
     * @return array
     */
    final public function getRules(array $mergeRules = [])
    {
        return $this->prepareTranslatedRulesOrMessages($this->rules($mergeRules));
    }

    /**
     * @param array $mergeMessages *
     *
     * @return array
     */
    final public function getErrorMessages(array $mergeMessages = [])
    {
        return $this->prepareTranslatedRulesOrMessages($this->errorMessages($mergeMessages));
    }

    /**
     * @param string|null $langPrefix
     * @param array       $mergeMessages
     *
     * @return array
     */
    final public function getErrorMessagesTranslated(string $langPrefix = null, array $mergeMessages = [])
    {
        $messages = $this->getErrorMessages($mergeMessages);

        $array = Arr::dot(array_map(function ($text) use ($langPrefix) {
            return __($langPrefix.$text);
        }, Arr::dot($messages)));

        return $array;
    }

    /**
     * Get rule by field name.
     *
     * @param string $field
     *
     * @return array
     */
    final public function getFieldRule(string $field)
    {
        return Arr::get($this->getRules(), $this->fieldNameToDot($field), []);
    }

    /**
     * Get rule by field name.
     *
     * @param string $field
     *
     * @return array
     */
    final public function getFieldErrorMessages(string $field)
    {
        $messages = $this->getErrorMessages();
        $fieldName = $this->fieldNameToDot($field);

        return isset($messages[$fieldName]) ? Arr::dot([$fieldName => $messages[$fieldName]]) : [];
    }

    /**
     * Get rule with custom error messages by field name.
     *
     * @param $field
     *
     * @return array
     */
    final public function getFieldRuleWithErrors(string $field)
    {
        return array_merge(['rules' => $this->getFieldRule($field)], ['error_messages' => $this->getFieldErrorMessages($field)]);
    }

    /**
     * Validation RULES.
     *
     * @param string $attr
     *
     * @return string
     */
    final protected function getIgnoreTextOnUpdate(string $attr = 'id')
    {
        return is_null($this->{$attr}) ? '' : ','.$this->{$attr};
    }

    private function prepareTranslatedRulesOrMessages(array $arrayValues)
    {
        if (!$this->modelIsTranslatable()) {
            return $arrayValues;
        }

        foreach ($this->getTranslatableAttributes() as $attribute) {
            if (in_array($attribute, array_keys($arrayValues))) {
                $rule = $arrayValues[$attribute];

                foreach ($this->requiredLocales() as $localeKey => $locale) {
                    $arrayValues["{$attribute}.{$localeKey}"] = $rule;
                }
                Arr::forget($arrayValues, $attribute);
            }
        }

        return $arrayValues;
    }

    /**
     * @return array
     */
    final protected function requiredLocales()
    {
        return LaravelLocalization::getSupportedLocales();
    }

    /**
     * Validation RULES.
     *
     * @param array $merge
     *
     * @return array
     */
    protected function rules(array $merge = [])
    {
        return array_merge($this->rules, $merge);
    }

    /**
     * Validation RULES.
     *
     * @param array $merge
     *
     * @return array
     */
    protected function errorMessages(array $merge = [])
    {
        return array_merge($this->errorMessages, $merge);
    }

    /**
     * @param string $field
     *
     * @return string
     */
    protected function fieldNameToDot(string $field)
    {
        return str_replace(']', '', str_replace('[', '.', $field));
    }

    /**
     * @return bool
     */
    private function modelIsTranslatable(): bool
    {
        return property_exists($this, 'translatable') and in_array(HasTranslations::class, class_uses_recursive($this));
    }
}

<?php


namespace GeoSot\BaseAdmin\App\Traits\Eloquent;

use Illuminate\Support\Arr;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

trait HasRulesOnModel
{

    protected $rules = [];
    protected $errorMessages = [];
    protected $translationRules = [];
    protected $translationErrorMessages = [];

    /**
     * @param array $mergeRules
     * @param array $mergeTranslationRules
     *
     * @return array
     */
    public function getRules(array $mergeRules = [], array $mergeTranslationRules = [])
    {
        return array_merge($this->rules($mergeRules), $this->prepareTranslations($this->translationRules($mergeTranslationRules)));
    }

    /**
     * @param array $mergeMessages
     * @param array $mergeTranslationMessages
     *
     * @return array
     */
    public function getErrorMessages(array $mergeMessages = [], array $mergeTranslationMessages = [])
    {
        return array_merge($this->errorMessages($mergeMessages), $this->prepareTranslations($this->translationErrorMessages($mergeTranslationMessages)));
    }

    /**
     * @param string|null $langPrefix
     * @param array       $mergeMessages
     * @param array       $mergeTranslationMessages
     *
     * @return array
     */
    public function getErrorMessagesTranslated(string $langPrefix = null, array $mergeMessages = [], array $mergeTranslationMessages = [])
    {
        $messages = $this->getErrorMessages($mergeMessages, $mergeTranslationMessages);

        $array = Arr::dot(array_map(function ($text) use ($langPrefix) {
            return __($langPrefix . $text);
        }, Arr::dot($messages)));

        return $array;
    }


    /**
     * Get rule by field name
     *
     * @param string $field
     *
     * @return array
     */
    public function getFieldRule(string $field)
    {
        return $this->getRules()[$field] ?? [];
    }


    /**
     * Get rule by field name
     *
     * @param string $field
     *
     * @return array
     */
    public function getFieldErrorMessages(string $field)
    {

        $messages = $this->getErrorMessages();

        return isset($messages[$field]) ?Arr::dot([$field => $messages[$field]]) : [];
    }

    /**
     * Get rule with custom error messages by field name
     *
     * @param $field
     *
     * @return array
     */
    public function getFieldRuleWithErrors(string $field)
    {
        return array_merge(['rules' => $this->getFieldRule($field)], ['error_messages' => $this->getFieldErrorMessages($field)]);
    }

    /**
     * Validation RULES
     *
     * @param  string $attr
     *
     * @return string
     */
    private function getIgnoreTextOnUpdate(string $attr = 'id')
    {
        return (is_null($this->{$attr}) ? '' : ',' . $this->{$attr});
    }


    private function prepareTranslations(array $arrayValues)
    {
        $values = [];
        foreach ($this->requiredLocales() as $localeKey => $locale) {
            foreach ($arrayValues as $attribute => $value) {
                $values[$localeKey . '.' . $attribute] = $value;
            }
        }

        return $values;
    }

    /**
     * @return array
     */
    protected function requiredLocales()
    {
        return LaravelLocalization::getSupportedLocales();
    }


    /**
     * Validation RULES
     *
     * @param  array $merge
     *
     * @return array
     */
    protected function rules(array $merge = [])
    {
        return array_merge($this->rules, $merge);
    }


    /**
     * Return an array of rules for translatable fields
     *
     * @param  array $merge
     *
     * @return array
     */
    protected function translationRules(array $merge = [])
    {
        return array_merge($this->translationRules, $merge);
    }


    /**
     * Validation RULES
     *
     * @param  array $merge
     *
     * @return array
     */
    protected function errorMessages(array $merge = [])
    {
        return array_merge($this->errorMessages, $merge);
    }


    /**
     * Return an array of messages for translatable fields
     *
     * @param  array $merge
     *
     * @return array
     */
    protected function translationErrorMessages(array $merge = [])
    {
        return array_merge($this->translationErrorMessages, $merge);
    }
}

<?php

namespace GeoSot\BaseAdmin\App\Forms\Site;

class ContactForm extends BaseFrontForm
{
//    protected $languageName='site/contact';
    public function getFormFields()
    {
        $this->setFormOptions($this->contactFormOptions());
        $rules = [
            'rules' => ['required'],
        ];
        $this->add('name', 'text', $rules);
        $this->add('email', 'email', $rules);
        $this->add('subject', 'text', $rules);
        $this->add('message', 'textarea', array_merge($rules, ['attr' => ['rows' => '5']]));

        $this->addCheckBox('agree_with_terms', array_merge($rules, [
            'help_block' => [
                'text' => $this->transHelpText('agree_with_terms')
            ]
        ]));

        $this->add('submit', 'submit', [
            'attr' => ['class' => 'btn btn-outline-primary'],
        ]);

    }

    /**
     * @return array
     */
    protected function contactFormOptions(): array
    {
        return [
            'method' => 'POST',
            'url' => route('site.contactUs.store'),
            'language_name' => 'baseAdmin::site/contact',
            'id' => 'contactForm',
        ];
    }
}

<?php

namespace GeoSot\BaseAdmin\App\Forms\Site;

class ContactForm extends BaseFrontForm
{

    protected $languageName = 'baseAdmin::site/contact.fields';

    public function getFormFields()
    {
        $this->setFormOptions($this->contactFormOptions());
        $this->add('name', 'text', [
            'rules' => ['required'],
        ]);
        $this->add('email', 'email', [
            'rules' => ['required','email'],
        ]);
        $this->add('subject', 'text',  [
            'rules' => ['required','max:100'],
        ]);
        $this->add('message', 'textarea', [
            'attr' => [
                'rows' => '5',
            ], 'rules' => ['required','min:50'],
        ]);

        $this->addCheckBox('agree_with_terms', [
            'rules' => ['required','accepted'],
            'help_block' => [
                'text' => $this->transHelpText('agree_with_terms'),
            ],
        ]);

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
            'id' => 'contactForm',
        ];
    }
}

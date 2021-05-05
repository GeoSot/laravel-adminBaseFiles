<?php

namespace GeoSot\BaseAdmin\App\Forms\Admin;

class BasicForm extends BaseAdminForm
{
    //    protected $formOptions = [
    //        'id' => 'mainForm',
    //    ];
    //
    public function getFormFields()
    {

        $modelInstance = $this->getModel();
        // $rules         = collect($modelInstance->rules());
        //        if ($modelInstance->id) {
        //            $this->modify('slug', 'text', [
        //                'attr' => ['readonly' => true]
        //            ]);
        //        }

        if (in_array('is_enabled', $modelInstance->getFillable())) {
            $this->addCheckBox('is_enabled');
        }
        if (in_array('title', $modelInstance->getFillable())) {
            $this->add('title', 'text');
        }

        if (in_array('slug', $modelInstance->getFillable())) {
            $this->add('slug', 'text', [
                'attr' => ['readonly' => 'readonly'],
            ]);
        }
        if (in_array('notes', $modelInstance->getFillable())) {
            $this->add('notes', 'textarea');
        }
    }
}

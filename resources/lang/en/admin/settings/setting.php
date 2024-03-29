<?php
return [
    'general' => [
        'menuTitle' => 'Settings',
        'singular' => 'Setting',
        'plural' => 'Settings',
    ],
    'fields' => [
        'id' => 'ID',
        'key' => 'Key',
        'value' => 'Value',
        'date_range_from' => 'Value',
        'date_range_to' => 'From Date',
        'value_dummy' => 'Till Date',
        'slug' => 'Slug',
        'group' => 'Group',
        'sub_group' => 'SubGroup',
        'model_type' => 'Related Entity',
        'model_id' => 'Model Name',
        'type' => 'Type',
        'notes' => 'Notes',
        'is_enabled' => 'Enabled',
        'ownerModel' => ['title' => 'Related Entity'],
        'type_to_human' => 'Type',
    ],


    'errorMessages' => ['keySubGroupGroupUnique' => 'The combination of key, subGroup and group has to be unique'],
    'formTitles' => [
        'relatedModel' => 'Related Entity',
        'value' => 'Setting Value',
    ],
    'fieldsHelpTexts' => [
        'model_type' => 'If you want the setting to be related with a model, please choose one',
        'type' => 'It doesn\'t change later',
        'settingsFieldsInfo' => 'The final Key (slug) will be created using the combination "group.subGroup.key".<br> For consistency reasons all the first letters will be lowercase '
    ],
    'buttons' => [
        'enableDevFields' => 'Edit fields',
        'enableDevFieldsDesc' => 'Handle with caution'
    ]
];

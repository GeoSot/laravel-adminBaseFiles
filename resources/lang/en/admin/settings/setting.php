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
        'slug' => 'Slug',
        'group' => 'Group',
        'sub_group' => 'SubGroup',
        'model_type' => 'Related Model',
        'model_id' => 'Model Name',
        'type' => 'Type',
        'notes' => 'Notes',
        'enabled' => 'Enabled',
        'ownerModel' => ['title' => 'Related Model'],
        'types' => [
            'string' => 'String',
            'textarea' => 'Textarea',
            'number' => 'Number',
            'timeToMinutes' => 'TimeToMinutes',
            'dateTime' => 'DateTime',
            'collectionSting' => 'Collection of Strings',
            'collectionNumber' => 'Collection of Numbers',
        ],
    ],


    'errorMessages' => ['keySubGroupGroupUnique' => 'The combination of key, subGroup and group has to be unique'],
    'formTitles' => [
        'relatedModel' => 'Related Model',
        'value' => 'Setting Value',
    ],
    'fieldsHelpTexts' => [
        'model_type' => 'If you want the setting to be related with a model, please choose one',
        'type' => 'It doesn\'t change later',
        'settingsFieldsInfo' => 'The final Key (slug) will be created using the combination "group.subGroup.key".<br> For consistency reasons all the first letters will by lowercase '
    ],
    'buttons' => [
        'enableDevFields' => 'Edit fields',
        'enableDevFieldsDesc' => 'Handle with caution'
    ]
];

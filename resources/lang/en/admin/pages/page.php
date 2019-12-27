<?php
return [
    'general' => [
        'menuTitle' => 'Pages',
        'singular' => 'Page',
        'plural' => 'Pages',
    ],
    'fields' => [
        'id' => 'ID',
        'title' => 'Title',
        'sub_title' => 'SubTitle',
        'notes' => 'Notes',
        'enabled' => 'Enabled',
        'meta_title' => 'Page Title (metaTags) ',
        'meta_description' => 'Description (metaTags)',
        'keywords' => 'Keywords (metaTags) ',
        'meta_tags' => 'Extra MetaTags ',
        'images' => 'Image',
        'slug' => 'Url Slug',
        'css' => 'Css',
        'javascript' => 'Javascript',
        'parentPage' => ['title' => 'Parent Page'],
        'childrenPages' => ['title' => 'Children Pages'],
    ],
    'fieldsHelpTexts' => [
        'meta_description' => 'up to 170 characters',
        'keywords' => 'separated with commas',
        'slug' => 'The Url slug'
    ],
    'formTitles' => [
        'metaTags' => 'Meta-Tags',
        'contentAreas' => 'Content Areas <span class=" small">(You can change their order inside each record form)</span>',
        'additionalScripts' => 'Additional Scripts to Page'
    ],
];

<?php
return [
    'app' => [
        'title' => 'Support Dash',
        'version' => '.v1'
    ],
    'menu' => [
        'dashboard' => 'Dashboard',
        'site' => 'Site',
        'listTitle' => 'List',
        'create' => 'Create',
        'edit' => 'Edit',
        'user' => [
            'profile' => 'My Profile',
            'logout' => 'Logout',
        ],
        'impersonation' => [
            'leave' => 'Leave impersonation',
            'isImpersonating' => 'You are impersonating',
        ],
    ],
    'sideMenuCustom' => [
        'configurations' => 'Configurations',
        'dotenveditor' => '.Env Editor',
        'logs' => 'Log Files',
        'translation' => 'Translations Manager'
    ],
    'messages' => [
        'crud' => [
            'create' => [
                'deny' => 'Action is denied',
            ],
            'edit' => [
                'deny' => 'Action is denied',
            ],
            'handle' => [
                'deny' => 'Action is denied. <br>It\'s not allowed to handle this record',
            ],
            'store' => [
                'deny' => 'Action is denied',
                'successTitle' => 'Success',
                'successMsg' => 'New record created successfully',
                'errorTitle' => 'Error',
                'errorMsg' => 'Store failed',
            ],
            'update' => [
                'deny' => 'Action is denied',
                'successTitle' => 'Success',
                'successMsg' => 'Record updated successfully',
                'errorTitle' => 'Error',
                'errorMsg' => 'Update failed',
            ],
            'changeStatus' => [
                'deny' => 'Action is denied',
                'successTitle' => 'Success',
                'successMsg' => ':num records were changed',
                'errorTitle' => 'Error',
                'errorMsg' => 'Records cannot be changed',
            ],
            'delete' => [
                'deny' => 'Action is denied',
                'successTitle' => 'Success',
                'successMsg' => 'Record deleted successfully|:num records deleted successfully',
                'errorTitle' => 'Error',
                'errorMsg' => 'Record cannot be deleted|Records cannot be deleted',
            ],
            'restore' => [
                'deny' => 'Action is denied',
                'successTitle' => 'Success',
                'successMsg' => 'Record restored successfully|:num records restored successfully',
                'errorTitle' => 'Error',
                'errorMsg' => 'Record cannot be restored|Records cannot be restored',
            ],
            'forceDelete' => [
                'deny' => 'Action is denied',
                'successTitle' => 'Success',
                'successMsg' => 'Record deleted permanently|:num records deleted permanently',
                'errorTitle' => 'Error',
                'errorMsg' => 'Record cannot be deleted permanently|Records cannot be deleted permanently',
            ],
        ],
    ],
    'button' => [
        'cancel' => 'Cancel',
        'change' => 'Change',
        'clearFilters' => 'Clear filters',
        'clearExtraFilters' => 'Clear extra filters',
        'create' => 'Create',
        'delete' => 'Delete',
        'disable' => 'Disable',
        'edit' => 'Edit',
        'enable' => 'Enable',
        'extraFilters' => 'Extra Filters',
        'makeCopy' => 'Make a new Copy',
        'permDelete' => 'Delete Permanently',
        'remove' => 'Remove',
        'restore' => 'Restore',
        'save' => 'Save',
        'saveAndClose' => 'Save & Close',
        'saveAndNew' => 'Save & New',
        'search' => 'Search',
        'selectFile' => 'Select File',
        'selectImage' => 'Select Image',
        'submit' => 'Submit',
        'trashed' => 'Trashed',
        'untrashed' => 'Untrashed',
        'update' => 'Update',
        'wrongFile' => 'You can upload only :types. </br> Please pick another file.',
    ],
    'listFilters' => [
        'search' => 'Search',
        'selectStatus' => 'Filter by status',
        'filterBy' => 'Filter by',
        'statusEnabled' => 'Enabled',
        'statusDisabled' => 'Disabled',
        'cleanFilters' => 'Clear Filters',
        'true' => 'True',
        'false' => 'False',
    ],
    'listMessages' => [
        'noRecordSelected' => 'Please select a record!',
        'noRecordSelected_msg' => 'No records selected.',
        'confirmDelete' => 'Are you sure?',
        'confirmDelete_msg' => 'Delete selected?',
        'confirmRestore' => 'Are you sure?',
        'confirmRestore_msg' => 'Restore selected?',
        'confirmForceDelete' => 'Are you sure?',
        'confirmForceDelete_msg' => 'Delete permanently selected?',
        'noResults' => '- - - No results - - -',
    ],
    'afterLoginPage' => [
        'msg' => 'Choose Page',
        'btnSite' => 'Site',
        'btnAdmin' => 'Dashboard',
    ],

    'permissionDenied' => [
        'title' => 'Warning',
        'msg' => "You don't have the proper Permission",
    ],
    'formTitles' => [
        'first' => 'Basic Details',
        'second' => 'Secondary Details',
        'third' => 'Extra Details',
        'relatedSettings' => 'Settings Related to this',
        'advancedSettings' => 'Advanced Settings',
        'formSelectPlaceHolder' => '- - Choose - -'
    ]


];
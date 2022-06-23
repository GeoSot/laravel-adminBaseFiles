<?php
return [
    'general' => [
        'menuTitle' => 'Users',
        'singular' => 'User',
        'plural' => 'Users',
    ],
    'fields' => [
        'id' => 'ID',
        'first_name' => 'First Name',
        'last_name' => 'Last Name',
        'full_name' => 'Full Name',
        'email' => 'E-mail',
        'preferred_lang' => 'Preferred language',
        'password' => 'Password',
        'password_confirmation' => 'Confirm Password',
        'current_password' => 'Current Password',
        'submit' => 'Update',
        'personalData' => 'Personal Data',
        'changePassword' => 'Change Password',
    ],
    'twoFa' => [
        'challenge' => [
            'title'=>'Two factor challenge (2FA)',
            'code' => 'Please enter your <strong>secret code</strong> to login',
            'recovery_code' => 'Please enter your <strong>recovery code (recovery)</strong> to login',
            'submit' => 'Submit',
            'useSecret' => 'Use secret code'
        ],
        'title' => "Two Factors Authentication",
        'enable' => 'Enable 2 FA',
        'enabled' => 'Two factor authentication has been enabled',
        'disable' => 'Disable 2 FA',
        'confirm2FaSubmit' => 'Confirm 2FA setup',
        'confirmCTA' => 'Please finish configuring two factor authentication below.',
        'scanQR' => 'Scan the following QR code using your phone\'s authenticator application or enter the setup key.',
        'code' => 'Confirm the two factor code by entering the OTP code from the authenticator application',
        'confirmed' => 'Two factor authentication confirmed and enabled successfully.',
        'setupKey' => 'Setup key:',
        'storeRecoveryCode' => 'Store these recovery codes in a secure place to recover access to your account if your two factor authentication device is lost.',
    ],
    'profileChange' => [
        'success' => [
            'msg' => 'Success',
            'title' => 'Your data were updated',
        ]
    ],
    'notifications' => [
        'createPasswordBtn' => 'Create Password'
    ]

];

<?php

return [
    'plugin' => [
        'name' => 'Hazelchat',
        'description' => 'Adds your Hazelchat AI chat agent to every page.',
    ],
    'permission' => 'Change the Hazelchat settings',
    'settings' => [
        'description' => 'Your installation code for the Hazelchat chat agent.',
        'code' => 'Installation code',
        'code_comment' => 'Paste the installation code from Hazelchat, or just its key. Leave it empty to remove the chat.',
        'consent' => 'Wait for my consent manager',
        'consent_comment' => 'The chat starts only once your cookie banner allows it.',
        'refused' => 'This is not a Hazelchat installation code. Copy it again from Hazelchat.',
        'signup' => 'Create a free Hazelchat account',
        'gdpr_cookie' => 'In your cookie banner\'s settings, add a cookie named Hazelchat, with the code hazelchat.',
    ],
];

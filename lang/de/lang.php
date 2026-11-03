<?php

return [
    'plugin' => [
        'name' => 'Hazelchat',
        'description' => 'Fügt Ihren Hazelchat-KI-Chat-Agenten in jede Seite ein.',
    ],
    'permission' => 'Hazelchat-Einstellungen ändern',
    'settings' => [
        'description' => 'Ihr Installationscode für den Hazelchat-Chat-Agenten.',
        'code' => 'Installationscode',
        'code_comment' => 'Fügen Sie den Installationscode aus Hazelchat ein, oder nur seinen Schlüssel. Leer lassen entfernt den Chat.',
        'consent' => 'Auf meinen Consent-Manager warten',
        'consent_comment' => 'Der Chat startet erst, wenn Ihr Cookie-Banner ihn erlaubt.',
        'refused' => 'Das ist kein Hazelchat-Installationscode. Kopieren Sie ihn erneut aus Hazelchat.',
        'signup' => 'Kostenloses Hazelchat-Konto erstellen',
        'gdpr_cookie' => 'Legen Sie in den Einstellungen Ihres Cookie-Banners ein Cookie namens Hazelchat mit dem Code hazelchat an.',
    ],
];

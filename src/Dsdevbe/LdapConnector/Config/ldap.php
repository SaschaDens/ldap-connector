<?php

return [
    'plugins' => [
        'adldap' => [
            'account_suffix'     => '@domain.local',
            'domain_controllers' => [
                '192.168.0.1',
                'dc02.domain.local',
            ], // Load balancing domain controllers
            'base_dn'        => 'DC=domain,DC=local',
            'admin_username' => 'admin', // This is required for session persistance in the application
            'admin_password' => 'yourPassword',
        ],
    ],
];

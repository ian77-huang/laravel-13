<?php

return [
    'title' => 'permission',
    'modules' => [
        'user' => 'User',
        'user_permissions' => 'User Permissions',
        'role' => 'Role',
        'broadcast' => 'Broadcast',
    ],
    'navigation' => [
        'permission' => 'Permission',
        'role' => 'Role',
        'member' => 'Member Management',
    ],
    'actions' => [
        'view' => 'View',
        'create' => 'Create',
        'edit' => 'Edit',
        'delete' => 'Delete',
    ],
    'guard' => 'Guards',
    'guards' => [
        'web' => 'Frontend(web)',
        'admin' => 'Backend(admin)',
        'api' => 'Api(api)',
    ],
];

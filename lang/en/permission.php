<?php

return [
    'title' => 'permission',
    'modules' => [
        'user' => 'User',
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

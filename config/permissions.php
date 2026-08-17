<?php

function createAction(string $key, string $value): array
{
    $actions = [];
    $actions[$key] = ['key' => $key, 'value' => $value];

    return $actions;
}

function createActions(?array $action = null): array
{
    $actions = [
        ...createAction('view', 'permission.actions.view'),
        ...createAction('create', 'permission.actions.create'),
        ...createAction('edit', 'permission.actions.edit'),
        ...createAction('delete', 'permission.actions.delete'),
    ];

    if ($action && is_array($action)) {
        $actions = array_merge($actions, $action);
    }

    return $actions;
}

return [
    'modules' => [
        'users' => 'permission.modules.user',
        'roles' => 'permission.modules.role',
    ],
    'actions' => [
        'users' => createActions(),
        'roles' => createActions(),
    ],
    'guards' => [
        'web' => [],
        'admin' => ['users', 'roles'],
        'api' => [],
    ],
];

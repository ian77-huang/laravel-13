<?php

if (! function_exists('createAction')) {
    function createAction(string $key, string $value): array
    {
        $actions = [];
        $actions[$key] = ['key' => $key, 'value' => $value];

        return $actions;
    }
}

if (! function_exists('createActions')) {
    function createActions(?array $action = null): array
    {
        $actions = [
            ...createAction('Create', 'filament-shield.Create'),
            ...createAction('Delete', 'filament-shield.Delete'),
            ...createAction('DeleteAny', 'filament-shield.DeleteAny'),
            ...createAction('ForceDelete', 'filament-shield.ForceDelete'),
            ...createAction('ForceDeleteAny', 'filament-shield.ForceDeleteAny'),
            ...createAction('Replicate', 'filament-shield.Replicate'),
            ...createAction('Reorder', 'filament-shield.Reorder'),
            ...createAction('Restore', 'filament-shield.Restore'),
            ...createAction('RestoreAny', 'filament-shield.RestoreAny'),
            ...createAction('Update', 'filament-shield.Update'),
            ...createAction('View', 'filament-shield.View'),
            ...createAction('ViewAny', 'filament-shield.ViewAny'),
        ];

        if ($action && is_array($action)) {
            $actions = array_merge($actions, $action);
        }

        return $actions;
    }
}

return [
    'modules' => [
        'User' => 'permission.modules.user',
        'Role' => 'permission.modules.role',
        'PermissionsUser' => 'permission.modules.user_permissions',
        'Broadcast' => 'permission.modules.broadcast',
    ],
    'actions' => [
        'User' => createActions(),
        'Role' => createActions(),
        'PermissionsUser' => [
            ...createAction('View', 'filament-shield.View'),
            ...createAction('Update', 'filament-shield.Update'),
        ],
        'Broadcast' => [
            ...createAction('View', 'filament-shield.View'),
            ...createAction('Update', 'filament-shield.Update'),
        ],
    ],
    'guards' => [
        'web' => ['User', 'PermissionsUser', 'Role', 'Broadcast'],
        'admin' => [],
        'api' => [],
    ],
];

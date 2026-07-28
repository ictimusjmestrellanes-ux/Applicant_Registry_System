<?php

return [

    [
        'label' => 'Dashboard',
        'icon' => 'bi bi-house',
        'route' => 'dashboard',
    ],

    [
        'label' => 'Applicants',
        'icon' => 'bi bi-person-plus',
        'route' => '#',
        'visible_roles' => ['admin', 'staff'],
        'children' => [
            ['label' => 'Add Applicant', 'route' => 'applicants.create', 'visible_roles' => ['admin', 'staff']],
            ['label' => 'List of Applicants', 'route' => 'applicants.index'],
            ['label' => 'Duplicate Applicants', 'route' => 'applicants.duplicates', 'visible_permissions' => ['view_duplicates']],
            ['label' => 'Archive Applicants', 'route' => 'applicants.archive', 'visible_permissions' => ['view_archive_applicants']],
            
            
        ],
    ],

    [
        'label' => 'User Management',
        'icon' => 'bi bi-people',
        'route' => 'users.index',
        'children' => [
            ['label' => 'Users', 'route' => 'users.index'],
            ['label' => 'Roles', 'route' => 'roles.index'],
            ['label' => 'Permissions', 'route' => 'permissions.index'],
        ],
        'visible_roles' => ['admin'],
    ],

    [
        'label' => 'Activity Logs',
        'icon' => 'bi bi-journal-text',
        'route' => 'activity-logs.index',
    ],
];

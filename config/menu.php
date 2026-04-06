<?php

return [

    [
        'label' => 'Dashboard',
        'icon' => 'bi bi-house',
        'route' => 'dashboard',
    ],

    [
        'label' => 'Users',
        'icon' => 'bi bi-people',
        'route' => 'users.index',
        'children' => [
            ['label' => 'All Users', 'route' => 'users.index'],
        ],
        'admin_only' => true,
    ],

    [
        'label' => 'Applicants',
        'icon' => 'bi bi-person-plus',
        'route' => '#',
        'children' => [
            ['label' => 'Add Applicant', 'route' => 'applicants.create'],
            ['label' => 'List of Applicants', 'route' => 'applicants.index'],
            ['label' => 'Archived Applicants', 'route' => 'applicants.archive'],
        ],
    ],

    [
        'label' => 'Activity Logs',
        'icon' => 'bi bi-journal-text',
        'route' => 'activity-logs.index',
    ],
];

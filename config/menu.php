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
            ['label' => 'Add User', 'route' => 'users.create'],
        ],
    ],

    [
        'label' => 'Applicants',
        'icon' => 'bi bi-person-plus',
        'route' => '#',
        'children' => [
            ['label' => 'Add Applicant', 'route' => 'applicants.create'],
            ['label' => 'List of Applicants', 'route' => 'applicants.index'],
        ],
    ],

    [
        'label' => 'Reports',
        'icon' => 'bi bi-graph-up',
        'route' => 'reports.index',
    ],

];
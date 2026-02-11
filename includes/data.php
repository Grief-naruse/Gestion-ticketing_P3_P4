<?php
// includes/data.php

// 1. Les Projets (Nouveau !)
$projects = [
    [
        'id' => 1,
        'name' => 'Refonte Site E-commerce',
        'client' => 'Boutique Mode SA',
        'type' => 'Maintenance (50h / an)',
        'hours_total' => 50,
        'hours_used' => 12,
        'team' => 'Ilan Rubaud, Sophie Martin'
    ],
    [
        'id' => 2,
        'name' => 'CRM Intranet',
        'client' => 'Assurance Plus',
        'type' => 'TMA Illimité',
        'hours_total' => 200, // Mis arbitrairement pour la barre de progression
        'hours_used' => 145,
        'team' => 'Ilan Rubaud'
    ]
];

// 2. Les Tickets
$tickets = [
    [
        'id' => 104,
        'title' => 'Erreur 500 au paiement',
        'project_id' => 1, // Lié à l'ID du projet
        'project' => 'Refonte Site E-commerce',
        'author' => 'Jean Dupont',
        'status' => 'status-progress', 
        'status_label' => 'En cours',
        'type' => 'type-included',
        'type_label' => 'Inclus',
        'priority' => 'Haute',
        'created_at' => '2026-02-01 09:00:00'
    ],
    [
        'id' => 105,
        'title' => 'Dév nouvelle feature PDF',
        'project_id' => 2,
        'project' => 'CRM Intranet',
        'author' => 'Ilan Rubaud',
        'status' => 'status-new', 
        'status_label' => 'À valider',
        'type' => 'type-billable',
        'type_label' => 'Facturable',
        'priority' => 'Moyenne',
        'created_at' => '2026-02-02 14:30:00'
    ]
];
?>
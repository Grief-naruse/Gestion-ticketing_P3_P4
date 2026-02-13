<?php
// includes/data.php
// Ce fichier sert maintenant à récupérer les VRAIES données depuis la BDD

// 1. On se connecte à la base
require_once 'db.php';

try {
    // --- RÉCUPÉRATION DES PROJETS AVEC SOMME DES HEURES ---
$sqlProjects = "SELECT p.*, 
                (SELECT SUM(te.duration) 
                FROM time_entries te 
                JOIN tickets t ON te.ticket_id = t.id 
                WHERE t.project_id = p.id) as real_hours_used
                FROM projects p";

$stmtProjects = $pdo->query($sqlProjects);
$projects = $stmtProjects->fetchAll(PDO::FETCH_ASSOC);
    // --- RÉCUPÉRATION DES TICKETS ---
    // On veut aussi le nom du projet associé, donc on fait une JOINTURE (LEFT JOIN)
    // Ça veut dire : "Prends les tickets ET va chercher le nom du projet correspondant dans l'autre table"
    $sqlTickets = "SELECT tickets.*, projects.name as project 
                   FROM tickets 
                   LEFT JOIN projects ON tickets.project_id = projects.id 
                   ORDER BY tickets.created_at DESC";
    
    $stmtTickets = $pdo->query($sqlTickets);
    $tickets = $stmtTickets->fetchAll(PDO::FETCH_ASSOC);

    // --- PETITE MOULINETTE D'AFFICHAGE ---
    // Pour garder ton affichage joli (les badges de couleurs), on ajoute les labels
    // car la BDD ne stocke que 'new', 'progress'... pas le texte 'En cours'.
    
  foreach ($tickets as &$ticket) {
    // Labels complets pour les statuts selon le Fil Rouge
    switch($ticket['status']) {
        case 'new':         $ticket['status_label'] = 'Nouveau'; break;
        case 'progress':    $ticket['status_label'] = 'En cours'; break;
        case 'waiting':     $ticket['status_label'] = 'En attente client'; break;
        case 'done':        $ticket['status_label'] = 'Terminé'; break;
        case 'to_validate': $ticket['status_label'] = 'À valider (client)'; break;
        case 'validated':   $ticket['status_label'] = 'Validé'; break;
        case 'refused':     $ticket['status_label'] = 'Refusé'; break;
        default:            $ticket['status_label'] = $ticket['status'];
    }
    // Classe CSS dynamique
    $ticket['status_class'] = 'status-' . $ticket['status']; 

    // Labels pour les types
    $ticket['type_label'] = ($ticket['type'] === 'included') ? 'Inclus' : 'Facturable';
    $ticket['type_class'] = ($ticket['type'] === 'included') ? 'type-included' : 'type-billable';
}
unset($ticket);

} catch (PDOException $e) {
    die("Erreur de récupération des données : " . $e->getMessage());
}
?>
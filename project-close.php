<?php
require 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['project_id'])) {
    $id = (int) $_POST['project_id'];
    try {
        // On supprime tout ce qui est lié au projet pour éviter les erreurs
        $pdo->prepare("DELETE FROM time_entries WHERE ticket_id IN (SELECT id FROM tickets WHERE project_id = :id)")->execute([':id' => $id]);
        $pdo->prepare("DELETE FROM tickets WHERE project_id = :id")->execute([':id' => $id]);
        $pdo->prepare("DELETE FROM projects WHERE id = :id")->execute([':id' => $id]);

        header("Location: projects.php?msg=deleted");
        exit();
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
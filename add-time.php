<?php
require 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticket_id = (int) $_POST['ticket_id'];
    $duration = (float) $_POST['duration'];
    $work_date = $_POST['work_date'];
    $comment = htmlspecialchars($_POST['comment']);
    $user_id = 1; // Temporaire : on utilise l'ID 1 (Ilan) en attendant l'étape 5

    if ($ticket_id > 0 && $duration > 0) {
        try {
            $sql = "INSERT INTO time_entries (ticket_id, user_id, duration, work_date, comment) 
                    VALUES (:tid, :uid, :dur, :date, :com)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':tid' => $ticket_id,
                ':uid' => $user_id,
                ':dur' => $duration,
                ':date' => $work_date,
                ':com' => $comment
            ]);

            // On redirige vers le ticket avec un message de succès
            header("Location: ticket-detail.php?id=" . $ticket_id . "&success=1");
            exit();
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
}
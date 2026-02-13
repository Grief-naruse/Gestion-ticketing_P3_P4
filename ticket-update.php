<?php
require 'includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int) $_POST['ticket_id'];
    $status = $_POST['status'];

    $sql = "UPDATE tickets SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':status' => $status, ':id' => $id]);

    header("Location: ticket-detail.php?id=" . $id . "&success=update");
    exit();
}
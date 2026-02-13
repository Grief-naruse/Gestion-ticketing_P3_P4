<?php
// includes/db.php

$host = 'localhost';
$dbname = 'ticketing_app';
$username = 'root'; // Par défaut sur XAMPP
$password = '';     // Par défaut sur XAMPP (vide)

try {
    // On crée la connexion (PDO)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // On configure pour voir les erreurs SQL s'il y en a
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Décommente la ligne ci-dessous juste pour tester, puis remets // devant !
    //echo "Connexion réussie à la base de données ! 🔌";

} catch (PDOException $e) {
    // Si ça plante, on arrête tout et on affiche l'erreur
    die("Erreur de connexion : " . $e->getMessage());
}
?>
<?php
require 'includes/db.php'; // Connexion BDD

$message = "";

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client = htmlspecialchars($_POST['client']);
    $name = htmlspecialchars($_POST['name']);
    $description = htmlspecialchars($_POST['description']);
    $type = htmlspecialchars($_POST['type']); // <-- On récupère le type ici
    $hours = (int) $_POST['hours'];
    $rate = (float) $_POST['rate'];

    if (!empty($client) && !empty($name) && !empty($hours)) {
        try {
            // REQUÊTE SQL QUI INCLUT LE TYPE
            $sql = "INSERT INTO projects (name, client_name, description, type, hours_total, rate, hours_used) 
                    VALUES (:name, :client, :descr, :type, :hours, :rate, 0)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name' => $name,
                ':client' => $client,
                ':descr' => $description,
                ':type' => $type, // <-- On l'envoie à la BDD
                ':hours' => $hours,
                ':rate' => $rate
            ]);

            header("Location: projects.php");
            exit();

        } catch (PDOException $e) {
            $message = '<div class="alert alert-error">❌ Erreur SQL : ' . $e->getMessage() . '</div>';
        }
    } else {
        $message = '<div class="alert alert-error">❌ Veuillez remplir les champs obligatoires.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Projet</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <aside class="sidebar">
        <h2>Ticketing App</h2>
        <nav>
            <ul>
                <li><a href="dashboard.php">Tableau de bord</a></li>
                <li><a href="projects.php" class="active">Projets</a></li>
                <li><a href="tickets.php">Tickets</a></li>
                <li><a href="settings.php">Paramètres</a></li>
            </ul>
        </nav>
    </aside>

    <main class="content">
        <header>
            <h1>Nouveau Projet</h1>
        </header>

        <?php if ($message)
            echo $message; ?>

        <form action="" method="POST" id="projectForm" class="card">
            <div class="form-group">
                <label for="client">Nom du Client *</label>
                <input type="text" id="client" name="client" placeholder="Ex: Microsoft" required>
            </div>

            <div class="form-group">
                <label for="p-name">Nom du projet *</label>
                <input type="text" id="p-name" name="name" placeholder="Ex: Refonte Intranet" required>
            </div>

            <div class="form-group">
                <label for="p-desc">Description</label>
                <textarea id="p-desc" name="description" rows="3"></textarea>
            </div>

            <div class="form-group">
                <label for="p-type">Type de contrat *</label>
                <select id="p-type" name="type" required>
                    <option value="Forfait">Forfait (Prix fixe)</option>
                    <option value="Régie">Régie (Temps passé)</option>
                    <option value="Maintenance">Maintenance (TMA)</option>
                    <option value="Interne">Projet Interne</option>
                </select>
            </div>

            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
            <h3>Configuration financière</h3>

            <div class="grid-2">
                <div class="form-group">
                    <label for="hours">Enveloppe d'heures *</label>
                    <input type="number" id="hours" name="hours" placeholder="Ex: 50" required>
                </div>
                <div class="form-group">
                    <label for="rate">Taux horaire (€/h)</label>
                    <input type="number" id="rate" name="rate" placeholder="Ex: 80" step="0.01">
                </div>
            </div>

            <div style="margin-top: 20px;">
                <button type="submit">Créer le projet</button>
                <a href="projects.php" class="btn" style="background-color: #95a5a6; margin-left: 10px;">Annuler</a>
            </div>
        </form>
    </main>
    <script src="js/app.js"></script>
</body>

</html>
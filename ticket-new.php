<?php
require 'includes/db.php';

// 1. Récupérer la liste des projets pour le menu déroulant
try {
    $stmt = $pdo->query("SELECT id, name FROM projects ORDER BY name ASC");
    $projectsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $projectsList = [];
}

$message = "";

// 2. Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $project_id = (int) $_POST['project_id'];
    $title = htmlspecialchars($_POST['title']);
    $description = htmlspecialchars($_POST['description']);
    $priority = $_POST['priority'];
    $type = $_POST['type'];

    if (!empty($title) && !empty($project_id)) {
        try {
            $sql = "INSERT INTO tickets (project_id, title, description, priority, type, status, created_at) 
                    VALUES (:pid, :title, :desc, :prio, :type, 'new', NOW())";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':pid' => $project_id,
                ':title' => $title,
                ':desc' => $description,
                ':prio' => $priority,
                ':type' => $type
            ]);

            header("Location: tickets.php");
            exit();

        } catch (PDOException $e) {
            $message = '<div class="alert alert-error">❌ Erreur : ' . $e->getMessage() . '</div>';
        }
    } else {
        $message = '<div class="alert alert-error">❌ Champs obligatoires manquants.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Ticket</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <aside class="sidebar">
        <h2>Ticketing App</h2>
        <nav>
            <ul>
                <li><a href="dashboard.php">Tableau de bord</a></li>
                <li><a href="projects.php">Projets</a></li>
                <li><a href="tickets.php" class="active">Tickets</a></li>
                <li><a href="settings.php">Paramètres</a></li>
            </ul>
        </nav>
    </aside>

    <main class="content">
        <header>
            <h1>Nouveau Ticket</h1>
        </header>

        <?php if($message) echo $message; ?>

        <form action="" method="POST" id="ticketForm" class="card">
            <div class="form-group">
                <label for="project">Projet concerné *</label>
                <select id="project" name="project_id" required>
                    <option value="">-- Choisir un projet --</option>
                    <?php foreach($projectsList as $p): ?>
                        <option value="<?php echo $p['id']; ?>">
                            <?php echo htmlspecialchars($p['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="title">Titre du ticket *</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div class="form-group">
                <label for="desc">Description détaillée</label>
                <textarea id="desc" name="description" rows="4"></textarea>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="priority">Priorité</label>
                    <select id="priority" name="priority">
                        <option value="low">Basse</option>
                        <option value="medium" selected>Moyenne</option>
                        <option value="high">Haute</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="type">Type d'intervention</label>
                    <select id="type" name="type">
                        <option value="included">Inclus (Maintenance)</option>
                        <option value="billable">Facturable</option>
                    </select>
                </div>
            </div>

            <div id="billable-warning" class="alert alert-warning hidden" style="margin-top: 10px;">
                ⚠️ Ce ticket sera facturé en supplément au client.
            </div>

            <div style="margin-top: 20px;">
                <button type="submit">Créer le ticket</button>
            </div>
        </form>
    </main>
    <script src="js/app.js"></script>
</body>
</html>
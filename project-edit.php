<?php
require 'includes/db.php'; //

$message = "";
$id = (int) $_GET['id'];

// 1. Récupérer les données actuelles pour remplir le formulaire
try {
    $stmt = $pdo->prepare("SELECT * FROM projects WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$project)
        die("Projet introuvable.");
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

// 2. Traiter la soumission du formulaire (UPDATE)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars($_POST['name']);
    $client = htmlspecialchars($_POST['client']);
    $description = htmlspecialchars($_POST['description']);
    $type = $_POST['type'];
    $hours = (int) $_POST['hours'];
    $rate = (float) $_POST['rate'];

    try {
        $sql = "UPDATE projects SET 
                name = :name, client_name = :client, description = :descr, 
                type = :type, hours_total = :hours, rate = :rate 
                WHERE id = :id";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':client' => $client,
            ':descr' => $description,
            ':type' => $type,
            ':hours' => $hours,
            ':rate' => $rate,
            ':id' => $id
        ]);

        header("Location: project-detail.php?id=$id&success=updated");
        exit();
    } catch (PDOException $e) {
        $message = '<div class="alert alert-error">❌ Erreur : ' . $e->getMessage() . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Modifier le Projet</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main class="content" style="margin-left: 0; width: 100%; max-width: 800px; margin: 0 auto;">
        <header>
            <h1>Modifier le projet : <?php echo htmlspecialchars($project['name']); ?></h1>
            <a href="project-detail.php?id=<?php echo $id; ?>" class="btn" style="background: #95a5a6;">Annuler</a>
        </header>

        <?php echo $message; ?>

        <form action="" method="POST" class="card">
            <div class="form-group">
                <label>Nom du Client</label>
                <input type="text" name="client" value="<?php echo htmlspecialchars($project['client_name']); ?>"
                    required>
            </div>
            <div class="form-group">
                <label>Nom du projet</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($project['name']); ?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description"
                    rows="3"><?php echo htmlspecialchars($project['description']); ?></textarea>
            </div>
            <div class="form-group">
                <label>Type de contrat</label>
                <select name="type">
                    <option value="Forfait" <?php if ($project['type'] == 'Forfait')
                        echo 'selected'; ?>>Forfait</option>
                    <option value="Régie" <?php if ($project['type'] == 'Régie')
                        echo 'selected'; ?>>Régie</option>
                    <option value="Maintenance" <?php if ($project['type'] == 'Maintenance')
                        echo 'selected'; ?>>
                        Maintenance</option>
                </select>
            </div>
            <div class="grid-2">
                <div class="form-group">
                    <label>Enveloppe d'heures</label>
                    <input type="number" name="hours" value="<?php echo $project['hours_total']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Taux horaire (€/h)</label>
                    <input type="number" name="rate" step="0.01" value="<?php echo $project['rate']; ?>">
                </div>
            </div>
            <button type="submit" class="btn">Enregistrer les modifications</button>
        </form>
    </main>
</body>

</html>
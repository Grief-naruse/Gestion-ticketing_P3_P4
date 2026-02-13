<?php
require 'includes/db.php'; // Connexion à la base de données

$id = (int)$_GET['id'];
$message = "";

// 1. Récupérer les infos du ticket
$stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = :id");
$stmt->execute([':id' => $id]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$ticket) die("Ticket introuvable.");

// 2. Récupérer tous les projets pour la liste déroulante
$projects = $pdo->query("SELECT id, name FROM projects ORDER BY name ASC")->fetchAll();

// 3. Traiter la modification
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $sql = "UPDATE tickets SET 
                title = :title, 
                description = :description, 
                project_id = :project_id, 
                type = :type, 
                status = :status 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title'       => htmlspecialchars($_POST['title']),
            ':description' => htmlspecialchars($_POST['description']),
            ':project_id'  => (int)$_POST['project_id'],
            ':type'        => $_POST['type'],
            ':status'      => $_POST['status'],
            ':id'          => $id
        ]);

        header("Location: ticket-detail.php?id=$id&success=updated");
        exit();
    } catch (PDOException $e) {
        $message = '<div class="alert alert-error">Erreur : ' . $e->getMessage() . '</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Ticket #<?php echo $id; ?></title>
    <link rel="stylesheet" href="css/style.css"> </head>
<body>
    <main class="content" style="margin-left: 0; max-width: 800px; margin: 20px auto;">
        <header>
            <h1>Modifier le Ticket #<?php echo $id; ?></h1>
            <a href="ticket-detail.php?id=<?php echo $id; ?>" class="btn" style="background: #95a5a6;">Annuler</a>
        </header>

        <?php echo $message; ?>

        <form action="" method="POST" class="card">
            <div class="form-group">
                <label>Titre du ticket</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($ticket['title']); ?>" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="5"><?php echo htmlspecialchars($ticket['description']); ?></textarea>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label>Projet associé</label>
                    <select name="project_id">
                        <?php foreach($projects as $p): ?>
                            <option value="<?php echo $p['id']; ?>" <?php echo ($p['id'] == $ticket['project_id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($p['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Type</label>
                    <select name="type">
                        <option value="included" <?php echo ($ticket['type'] == 'included') ? 'selected' : ''; ?>>Inclus (Maintenance)</option>
                        <option value="billable" <?php echo ($ticket['type'] == 'billable') ? 'selected' : ''; ?>>Facturable (Hors forfait)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Statut actuel</label>
                <select name="status">
                    <option value="new" <?php echo ($ticket['status'] == 'new') ? 'selected' : ''; ?>>Nouveau</option>
                    <option value="progress" <?php echo ($ticket['status'] == 'progress') ? 'selected' : ''; ?>>En cours</option>
                    <option value="waiting" <?php echo ($ticket['status'] == 'waiting') ? 'selected' : ''; ?>>En attente client</option>
                    <option value="done" <?php echo ($ticket['status'] == 'done') ? 'selected' : ''; ?>>Terminé</option>
                </select>
            </div>

            <button type="submit" class="btn">Enregistrer les changements</button>
        </form>
    </main>
</body>
</html>
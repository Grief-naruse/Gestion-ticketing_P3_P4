<?php
require 'includes/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) { die("❌ ID manquant."); }
$id = (int) $_GET['id'];

try {
    $sql = "SELECT p.*, (SELECT SUM(te.duration) FROM time_entries te JOIN tickets t ON te.ticket_id = t.id WHERE t.project_id = p.id) as real_hours_used FROM projects p WHERE p.id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $project = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$project) die("❌ Projet introuvable.");

    $used = $project['real_hours_used'] ?? 0;
    $percent = ($project['hours_total'] > 0) ? ($used / $project['hours_total']) * 100 : 0;

    $stmtTickets = $pdo->prepare("SELECT * FROM tickets WHERE project_id = :id ORDER BY created_at DESC");
    $stmtTickets->execute([':id' => $id]);
    $tickets = $stmtTickets->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) { die("Erreur : " . $e->getMessage()); }

// Ajoute ce dictionnaire de traduction
$statusLabels = [
    'new'         => 'Nouveau',
    'progress'    => 'En cours',
    'waiting'     => 'En attente client',
    'done'        => 'Terminé',
    'to_validate' => 'À valider (client)',
    'validated'   => 'Validé',
    'refused'     => 'Refusé'
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Projet : <?php echo htmlspecialchars($project['name']); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <aside class="sidebar">
        <h2>Ticketing App</h2>
        <nav><ul><li><a href="dashboard.php">Tableau de bord</a></li><li><a href="projects.php" class="active">Projets</a></li><li><a href="tickets.php">Tickets</a></li></ul></nav>
    </aside>

    <main class="content">
        <a href="projects.php" style="text-decoration: none; color: #7f8c8d;">← Retour aux projets</a>
        
        <header style="margin-top: 15px; display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h1><?php echo htmlspecialchars($project['name']); ?></h1>
                <p>Client : <strong><?php echo htmlspecialchars($project['client_name']); ?></strong></p>
            </div>
            
            <div style="display: flex; gap: 10px;">
                <a href="project-edit.php?id=<?php echo $id; ?>" class="btn">Modifier</a>
                
                <form action="project-close.php" method="POST" onsubmit="return confirm('Clôturer ce projet ?');">
                    <input type="hidden" name="project_id" value="<?php echo $id; ?>">
                    <button type="submit" class="btn" style="background-color: #e67e22;">Clôturer</button>
                </form>

                <form action="project-close.php" method="POST" onsubmit="return confirm('⚠️ SUPPRIMER DÉFINITIVEMENT ?');">
                    <input type="hidden" name="project_id" value="<?php echo $id; ?>">
                    <button type="submit" class="btn" style="background-color: #e74c3c;">Supprimer</button>
                </form>
            </div>
        </header>

        <div class="grid-2">
            <div class="card">
                <h3>Suivi du Contrat</h3>
                <p>Type : <?php echo htmlspecialchars($project['type']); ?></p>
                <div style="background: #eee; height: 15px; border-radius: 10px; margin: 15px 0;">
                    <div style="background: #3498db; width: <?php echo min($percent, 100); ?>%; height: 100%; border-radius: 10px;"></div>
                </div>
                <p><?php echo number_format($used, 2); ?>h / <?php echo number_format($project['hours_total'], 2); ?>h</p>
            </div>
            <div class="card">
                <h3>Équipe assignée</h3>
                <span class="badge" style="background-color: #f1c40f; color: #333;">Admin</span>
            </div>
        </div>

        <div class="card" style="margin-top: 20px;">
            <h3>Tickets rattachés</h3>
            <table style="width: 100%;">
                <thead><tr><th>ID</th><th>Titre</th><th>Type</th><th>Statut</th><th>Action</th></tr></thead>
                <tbody>
                    <?php foreach($tickets as $t): ?>
                    <tr>
                        <td>#<?php echo $t['id']; ?></td>
                        <td><?php echo htmlspecialchars($t['title']); ?></td>
                        <td><span class="badge type-<?php echo $t['type']; ?>"><?php echo $t['type']; ?></span></td>
                        <td style="padding: 10px;"><span class="badge status-<?php echo $t['status']; ?>"><?php echo $statusLabels[$t['status']] ?? $t['status']; ?></span></td>
                        <td><a href="ticket-detail.php?id=<?php echo $t['id']; ?>">Voir</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
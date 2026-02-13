<?php
require 'includes/db.php'; // Connexion BDD

// 1. Vérification de l'ID du ticket
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("❌ Erreur : ID du ticket manquant.");
}

$ticket_id = (int) $_GET['id'];

try {
    // 2. Récupération des infos du ticket avec jointures (Projet + Auteur + Assigné)
    // u1 = Auteur, u2 = Assigné
    $sql = "SELECT tickets.*, projects.name as project_name, 
                u1.name as author_name, u2.name as assigned_name
        FROM tickets 
        LEFT JOIN projects ON tickets.project_id = projects.id 
        LEFT JOIN users u1 ON tickets.author_id = u1.id
        LEFT JOIN users u2 ON tickets.assigned_to = u2.id
        WHERE tickets.id = :id";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $ticket_id]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ticket) {
        die("❌ Erreur : Ticket introuvable.");
    }

    // 3. Récupération des entrées de temps pour ce ticket
    $stmtTime = $pdo->prepare("SELECT * FROM time_entries WHERE ticket_id = :tid ORDER BY work_date DESC");
    $stmtTime->execute([':tid' => $ticket_id]);
    $timeEntries = $stmtTime->fetchAll(PDO::FETCH_ASSOC);

    // 4. Calcul du temps total passé sur ce ticket
    $totalTicketTime = 0;
    foreach($timeEntries as $entry) {
        $totalTicketTime += $entry['duration'];
    }

    // 5. Préparation des labels et couleurs (Logique Fil Rouge)
    $statusLabels = [
        'new'         => 'Nouveau',
        'progress'    => 'En cours',
        'waiting'     => 'En attente client',
        'done'        => 'Terminé',
        'to_validate' => 'À valider (client)',
        'validated'   => 'Validé',
        'refused'     => 'Refusé'
    ];
    $currentStatusLabel = $statusLabels[$ticket['status']] ?? $ticket['status'];
    $statusClass = 'status-' . $ticket['status']; //

} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail Ticket #<?php echo $ticket['id']; ?></title>
    <link rel="stylesheet" href="css/style.css"> </head>
<body>
    <aside class="sidebar"> <h2>Ticketing App</h2>
        <nav>
            <ul>
                <li><a href="dashboard.php">Tableau de bord</a></li>
                <li><a href="projects.php">Projets</a></li>
                <li><a href="tickets.php" class="active">Tickets</a></li>
                <li><a href="settings.php">Paramètres</a></li>
            </ul>
        </nav>
        <div class="user-info">
             <p style="margin-bottom: 5px; font-size: 0.8rem; opacity: 0.7;">Connecté en tant que :</p>
             <a href="profile.php" style="color: white; font-weight: bold; text-decoration: none;">Ilan Rubaud</a> </div>
    </aside>

    <main class="content">
        <a href="tickets.php" style="text-decoration: none; color: #7f8c8d;">← Retour à la liste</a>
        
        <?php if(isset($_GET['success'])): ?>
            <div class="alert alert-success" style="margin-top:15px;">✅ Temps enregistré avec succès !</div>
        <?php endif; ?>

        <header style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
            <h1>#<?php echo $ticket['id']; ?> - <?php echo htmlspecialchars($ticket['title']); ?></h1>
            <div>
                <span class="badge <?php echo ($ticket['type'] === 'included') ? 'type-included' : 'type-billable'; ?>">
                    <?php echo ($ticket['type'] === 'included') ? 'Inclus' : 'Facturable'; ?>
                </span>
                <span class="badge <?php echo $statusClass; ?>"><?php echo $currentStatusLabel; ?></span>
            </div>
        </header>

        <div class="grid-2"> <div>
                <div class="card">
                    <h3>Description</h3>
                    <p style="line-height: 1.6; color: #2c3e50; margin-top: 10px;">
                        <?php echo nl2br(htmlspecialchars($ticket['description'])); ?>
                    </p>
                </div>

                <div class="card" style="margin-top: 20px;">
                    <h3>⏱️ Enregistrer du temps</h3>
                    <form action="add-time.php" method="POST" style="margin-top: 15px;">
                        <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
                        <div class="grid-2">
                            <div class="form-group">
                                <label>Durée (en heures)</label>
                                <input type="number" name="duration" step="0.25" min="0.25" placeholder="ex: 1.5" required>
                            </div>
                            <div class="form-group">
                                <label>Date de l'intervention</label>
                                <input type="date" name="work_date" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Commentaire / Travail effectué</label>
                            <textarea name="comment" rows="2" placeholder="Qu'avez-vous fait ?"></textarea>
                        </div>
                        <button type="submit" class="btn" style="width: 100%;">Ajouter les heures</button>
                    </form>
                </div>

                <div class="card" style="margin-top: 20px;">
                    <h3>Historique des interventions</h3>
                    <?php if(empty($timeEntries)): ?>
                        <p style="margin-top:10px; color:#7f8c8d;">Aucun temps enregistré pour le moment.</p>
                    <?php else: ?>
                        <table style="margin-top: 15px; font-size: 0.9rem;">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Durée</th>
                                    <th>Commentaire</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($timeEntries as $entry): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($entry['work_date'])); ?></td>
                                    <td><strong><?php echo $entry['duration']; ?>h</strong></td>
                                    <td><?php echo htmlspecialchars($entry['comment']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <div class="card">
                    <h3>Informations</h3>
                    <p><strong>Projet :</strong> <?php echo htmlspecialchars($ticket['project_name']); ?></p>
                    <p><strong>Priorité :</strong> <?php echo htmlspecialchars($ticket['priority']); ?></p>
                    
                    <p><strong>Assigné à :</strong> <span class="badge" style="background:#eee; color:#333; min-width:auto;"><?php echo htmlspecialchars($ticket['assigned_name'] ?? 'Non assigné'); ?></span></p>
                    <p><strong>Créé par :</strong> <?php echo htmlspecialchars($ticket['author_name'] ?? 'Système'); ?></p>
                    <p><strong>Date de création :</strong> <span id="ticket-creation-date" data-created="<?php echo $ticket['created_at']; ?>"><?php echo date('d/m/Y H:i', strtotime($ticket['created_at'])); ?></span></p>

                    <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
                    
                    <div style="text-align: center;">
                        <p style="font-size: 0.9rem; color: #7f8c8d;">Temps total passé sur ce ticket</p>
                        <div style="font-size: 2rem; font-weight: bold; color: var(--accent-color);">
                            <?php echo $totalTicketTime; ?>h
                        </div>
                    </div>

                    <div style="text-align: center; margin-top: 15px; padding: 10px; background: #f9f9f9; border-radius: 4px;">
                        <p style="font-size: 0.8rem; color: #7f8c8d; text-transform: uppercase;">Ouvert depuis</p>
                        <div id="live-timer" style="font-family: monospace; font-size: 1.2rem; font-weight: bold;">00h 00m 00s</div>
                    </div>
                </div>
                
                <div class="card" style="margin-top: 20px;">
    <h3>Changer le statut</h3>
    <form action="ticket-update.php" method="POST" style="margin-top: 10px;">
        <input type="hidden" name="ticket_id" value="<?php echo $ticket['id']; ?>">
        <select name="status" style="margin-bottom: 10px;">
            <?php foreach($statusLabels as $key => $label): ?>
                <option value="<?php echo $key; ?>" <?php echo ($ticket['status'] == $key) ? 'selected' : ''; ?>>
                    <?php echo $label; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn" style="width: 100%; background-color: var(--accent-color);">Mettre à jour</button>
    </form>
</div>
            </div>
        </div>
    </main>
    <script src="js/app.js"></script> </body>
</html>
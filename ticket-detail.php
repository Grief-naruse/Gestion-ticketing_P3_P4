<?php
// On récupère les données
require 'includes/data.php';

// On regarde l'URL pour trouver ?id=...
$ticket_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$currentTicket = null;

// On cherche le ticket correspondant dans le tableau
foreach ($tickets as $t) {
    if ($t['id'] === $ticket_id) {
        $currentTicket = $t;
        break;
    }
}

// Si on n'a rien trouvé
if (!$currentTicket) {
    die("❌ Erreur : Ticket introuvable. <a href='tickets.php'>Retour</a>");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détail Ticket #<?php echo $ticket_id; ?></title>
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
        <div class="user-info">
             <p style="margin-bottom: 5px; font-size: 0.8rem; opacity: 0.7;">Connecté en tant que :</p>
             <a href="profile.php" style="color: white; font-weight: bold; text-decoration: none;">Ilan Rubaud</a>
             <div style="margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 10px;">
                <a href="index.php" style="color: #bdc3c7; font-size: 0.8rem; text-decoration: none;">➜ Déconnexion</a>
            </div>
        </div>
    </aside>

    <main class="content">
        <a href="tickets.php" style="text-decoration: none; color: #7f8c8d;">← Retour à la liste</a>
        
        <header style="margin-top: 20px;">
            <h1>#<?php echo $currentTicket['id']; ?> - <?php echo htmlspecialchars($currentTicket['title']); ?></h1>
            <div style="display: flex; gap: 10px;">
                <span class="badge <?php echo $currentTicket['type']; ?>"><?php echo $currentTicket['type_label']; ?></span>
                <span class="badge <?php echo $currentTicket['status']; ?>"><?php echo $currentTicket['status_label']; ?></span>
            </div>
        </header>

        <div class="grid-2">
            <div>
                <article class="card">
                    <h3>Description</h3>
                    <p>Description simulée pour le ticket <?php echo $ticket_id; ?>.</p>
                </article>

                <article class="card">
                    <h3>Historique</h3>
                    <div style="border-left: 3px solid #ddd; padding-left: 10px; margin-bottom: 15px;">
                        <strong><?php echo $currentTicket['author']; ?></strong> <small><?php echo $currentTicket['created_at']; ?></small>
                        <p>Ticket créé.</p>
                    </div>
                </article>
            </div>

            <div>
                <aside class="card">
                    <h3>Informations</h3>
                    <ul style="list-style: none;">
                        <li style="margin-bottom: 10px;"><strong>Projet :</strong> <?php echo htmlspecialchars($currentTicket['project']); ?></li>
                        <li style="margin-bottom: 10px;"><strong>Priorité :</strong> <?php echo $currentTicket['priority']; ?></li>
                        <li style="margin-bottom: 10px;"><strong>Assigné à :</strong> <?php echo $currentTicket['author']; ?></li>
                        <li style="margin-bottom: 10px;">
                            <strong>Créé le :</strong> 
                            <span id="ticket-creation-date" data-created="<?php echo $currentTicket['created_at']; ?>">
                                <?php echo $currentTicket['created_at']; ?>
                            </span>
                        </li>
                    </ul>
                    <hr style="margin: 15px 0; border: 0; border-top: 1px solid #eee;">
                    
                    <div style="text-align: center; margin-bottom: 15px;">
                        <small style="color: #666; display: block; margin-bottom: 5px;">Temps écoulé</small>
                        <div id="live-timer" style="font-family: monospace; font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">--j --h --m --s</div>
                    </div>
                </aside>
            </div>
        </div>
    </main>
    <script src="js/app.js"></script>
</body>
</html>
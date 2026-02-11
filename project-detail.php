<?php
require 'includes/data.php';

// Récupération ID Projet
$project_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$currentProject = null;

// Trouver le projet
foreach ($projects as $p) {
    if ($p['id'] === $project_id) {
        $currentProject = $p;
        break;
    }
}

if (!$currentProject) die("❌ Projet introuvable.");

// Calculs pour la barre
$percent = ($currentProject['hours_used'] / $currentProject['hours_total']) * 100;
$remaining = $currentProject['hours_total'] - $currentProject['hours_used'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projet : <?php echo htmlspecialchars($currentProject['name']); ?></title>
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
        <div class="user-info">
             <p style="margin-bottom: 5px; font-size: 0.8rem; opacity: 0.7;">Connecté en tant que :</p>
             <a href="profile.php" style="color: white; font-weight: bold; text-decoration: none;">Ilan Rubaud</a>
             <div style="margin-top: 10px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 10px;">
                <a href="index.php" style="color: #bdc3c7; font-size: 0.8rem; text-decoration: none;">➜ Déconnexion</a>
            </div>
        </div>
    </aside>

    <main class="content">
        <a href="projects.php" style="text-decoration: none; color: #7f8c8d;">← Retour aux projets</a>
        
        <header style="margin-top: 20px;">
            <div>
                <h1><?php echo htmlspecialchars($currentProject['name']); ?></h1>
                <p style="color: #666;">Client : <strong><?php echo htmlspecialchars($currentProject['client']); ?></strong></p>
            </div>
        </header>

        <section class="grid-2">
            <div class="card">
                <h3>Suivi du Contrat</h3>
                <p><strong>Type :</strong> <?php echo htmlspecialchars($currentProject['type']); ?></p>
                
                <div style="background: #eee; height: 15px; border-radius: 10px; margin-top: 10px;">
                    <div style="background: var(--accent-color); width: <?php echo $percent; ?>%; height: 100%; border-radius: 10px;"></div>
                </div>
                
                <p style="margin-top: 5px; font-size: 0.9rem;">
                    <?php echo $currentProject['hours_used']; ?>h consommées / <?php echo $remaining; ?>h restantes
                </p>
            </div>
            
            <div class="card">
                <h3>Équipe</h3>
                <p><?php echo htmlspecialchars($currentProject['team']); ?></p>
            </div>
        </section>

        <section class="card">
            <h3>Tickets du projet</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $hasTickets = false;
                    foreach($tickets as $ticket): 
                        // Filtre par ID de projet ou Nom de projet
                        if(isset($ticket['project_id']) && $ticket['project_id'] === $project_id): 
                            $hasTickets = true;
                    ?>
                    <tr>
                        <td>#<?php echo $ticket['id']; ?></td>
                        <td><?php echo htmlspecialchars($ticket['title']); ?></td>
                        <td><span class="badge <?php echo $ticket['status']; ?>"><?php echo $ticket['status_label']; ?></span></td>
                        <td><a href="ticket-detail.php?id=<?php echo $ticket['id']; ?>">Voir</a></td>
                    </tr>
                    <?php endif; endforeach; ?>

                    <?php if(!$hasTickets): ?>
                        <tr><td colspan="4" style="text-align:center;">Aucun ticket pour ce projet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
    <script src="js/app.js"></script>
</body>
</html>
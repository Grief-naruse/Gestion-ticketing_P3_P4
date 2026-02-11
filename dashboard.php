<?php
require 'includes/data.php';

// --- CALCULS DYNAMIQUES (Stats) ---
$nbTickets = count($tickets); // Compte total
$nbProjets = count($projects);

// Compter les tickets "À valider"
$nbToValidate = 0;
foreach($tickets as $t) {
    if ($t['status'] === 'status-new') $nbToValidate++;
}

// Calculer les heures totales consommées (sur tous les projets)
$totalHoursUsed = 0;
foreach($projects as $p) {
    $totalHoursUsed += $p['hours_used'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <aside class="sidebar">
        <h2>Ticketing App</h2>
        <nav>
            <ul>
                <li><a href="dashboard.php" class="active">Tableau de bord</a></li>
                <li><a href="projects.php">Projets</a></li>
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
        <header>
            <h1>Tableau de bord</h1>
            <div class="actions">
                <a href="project-new.php" class="btn" style="background-color: #7f8c8d; margin-right: 10px;">+ Nouveau Projet</a>
                <a href="ticket-new.php" class="btn">+ Nouveau Ticket</a>
            </div>
        </header>

        <section class="grid-4">
            <div class="card">
                <h3>Tickets Total</h3>
                <p class="animate-number" style="font-size: 2rem; font-weight: bold; color: var(--accent-color);"><?php echo $nbTickets; ?></p>
            </div>
            <div class="card">
                <h3>À valider (Client)</h3>
                <p class="animate-number" style="font-size: 2rem; font-weight: bold; color: var(--warning-color);"><?php echo $nbToValidate; ?></p>
            </div>
            <div class="card">
                <h3>Projets actifs</h3>
                <p class="animate-number" style="font-size: 2rem; font-weight: bold; color: #9b59b6;"><?php echo $nbProjets; ?></p>
            </div>
            <div class="card">
                <h3>Heures consommées</h3>
                <p class="animate-number" style="font-size: 2rem; font-weight: bold;"><?php echo $totalHoursUsed; ?>h</p>
            </div>
        </section>

        <section class="card">
            <h3>Derniers tickets mis à jour</h3>
            <table>
                <thead>
                    <tr>
                        <th>Projet</th>
                        <th>Titre</th>
                        <th>Statut</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // On inverse le tableau pour avoir les plus récents en premier (simulation)
                    $reversedTickets = array_reverse($tickets);
                    $lastTickets = array_slice($reversedTickets, 0, 5);
                    
                    foreach($lastTickets as $ticket): 
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ticket['project']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['title']); ?></td>
                        <td><span class="badge <?php echo $ticket['status']; ?>"><?php echo $ticket['status_label']; ?></span></td>
                        <td><?php echo date('d/m/Y', strtotime($ticket['created_at'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
    <script src="js/app.js"></script>
</body>
</html>
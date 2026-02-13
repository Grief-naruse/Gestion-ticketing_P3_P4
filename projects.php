<?php
// On inclut les données (qui doivent contenir la requête avec SUM(duration))
require 'includes/data.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Projets</title>
    <link rel="stylesheet" href="css/style.css"> </head>
<body>
    <aside class="sidebar"> <h2>Ticketing App</h2>
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
             <a href="profile.php" style="color: white; font-weight: bold; text-decoration: none;">Ilan Rubaud</a> </div>
    </aside>

    <main class="content">
        <header>
            <h1>Projets Client</h1>
            <a href="project-new.php" class="btn">+ Nouveau Projet</a>
        </header>

        <div style="margin-bottom: 20px;">
            <input type="text" id="project-search" placeholder="🔍 Rechercher un projet..." style="width: 100%; max-width: 300px;">
        </div>

        <?php if(empty($projects)): ?>
            <div class="alert alert-warning">Aucun projet trouvé. Commencez par en créer un !</div>
        <?php else: ?>

        <section class="grid-2">
            <?php foreach($projects as $project): ?>
                <?php 
                    // On récupère les heures réelles agrégées
                    $used = $project['real_hours_used'] ?? 0;
                    
                    // Calcul du pourcentage pour la barre de progression
                    $percent = ($project['hours_total'] > 0) ? ($used / $project['hours_total']) * 100 : 0;

                    // Couleur de la barre : Rouge si > 90%, sinon Vert
                    $barColor = ($percent > 90) ? 'var(--danger-color)' : 'var(--success-color)';
                ?>
                <article class="card">
                    <h3>
                        <a href="project-detail.php?id=<?php echo $project['id']; ?>" style="text-decoration: none; color: inherit;">
                            <?php echo htmlspecialchars($project['name']); ?>
                        </a>
                    </h3>
                    
                    <p><strong>Client :</strong> <?php echo htmlspecialchars($project['client_name']); ?></p>
                    <p><strong>Contrat :</strong> <span class="badge"><?php echo htmlspecialchars($project['type']); ?></span></p>
                    
                    <?php if(!empty($project['description'])): ?>
                        <p style="font-size: 0.85rem; color: #666; margin-top: 5px; font-style: italic;">
                            <?php echo htmlspecialchars(substr($project['description'], 0, 50)) . '...'; ?>
                        </p>
                    <?php endif; ?>

                    <hr style="margin: 10px 0; border: 0; border-top: 1px solid #eee;">
                    
                    <p>Heures consommées : <strong><?php echo number_format($used, 2); ?>h / <?php echo $project['hours_total']; ?>h</strong></p>
                    
                    <div style="background: #eee; height: 10px; border-radius: 5px; margin-top: 5px;">
                        <div style="background: <?php echo $barColor; ?>; width: <?php echo min($percent, 100); ?>%; height: 100%; border-radius: 5px;"></div>
                    </div>
                    
                    <div style="margin-top: 15px;">
                        <a href="project-detail.php?id=<?php echo $project['id']; ?>" class="btn" style="font-size: 0.8rem; background-color: #7f8c8d;">Détails du projet</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>

        <?php endif; ?>
    </main>
    <script src="js/app.js"></script> </body>
</html>
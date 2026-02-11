<?php
require 'includes/data.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Projets</title>
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
        <header>
            <h1>Projets Client</h1>
            <a href="project-new.php" class="btn">+ Nouveau Projet</a>
        </header>

        <div style="margin-bottom: 20px;">
            <input type="text" id="project-search" placeholder="🔍 Rechercher un projet..." style="width: 100%; max-width: 300px;">
        </div>

        <section class="grid-2">
            <?php foreach($projects as $project): ?>
                <?php 
                    // Calcul du pourcentage pour la barre de progression
                    $percent = ($project['hours_used'] / $project['hours_total']) * 100;
                    // Couleur de la barre : Rouge si > 90%, sinon Vert
                    $barColor = ($percent > 90) ? 'var(--danger-color)' : 'var(--success-color)';
                ?>
                <article class="card">
                    <h3>
                        <a href="project-detail.php?id=<?php echo $project['id']; ?>" style="text-decoration: none; color: inherit;">
                            <?php echo htmlspecialchars($project['name']); ?>
                        </a>
                    </h3>
                    <p><strong>Client :</strong> <?php echo htmlspecialchars($project['client']); ?></p>
                    <p><strong>Contrat :</strong> <?php echo htmlspecialchars($project['type']); ?></p>
                    
                    <hr style="margin: 10px 0; border: 0; border-top: 1px solid #eee;">
                    
                    <p>Heures consommées : <strong><?php echo $project['hours_used']; ?>h / <?php echo $project['hours_total']; ?>h</strong></p>
                    
                    <div style="background: #eee; height: 10px; border-radius: 5px; margin-top: 5px;">
                        <div style="background: <?php echo $barColor; ?>; width: <?php echo $percent; ?>%; height: 100%; border-radius: 5px;"></div>
                    </div>
                    
                    <div style="margin-top: 15px;">
                        <a href="project-detail.php?id=<?php echo $project['id']; ?>" class="btn" style="font-size: 0.8rem; background-color: #7f8c8d;">Détails</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </section>
    </main>
    <script src="js/app.js"></script>
</body>
</html>
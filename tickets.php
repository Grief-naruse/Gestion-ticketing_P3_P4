<?php
// On charge les données simulées
require 'includes/data.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Tickets</title>
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
        <header>
            <h1>Tous les tickets</h1>
            <a href="ticket-new.php" class="btn">+ Nouveau Ticket</a>
        </header>

        <div class="card">
           <div style="margin-bottom: 20px;">
    <select id="filter-project" style="width: 200px; display: inline-block; margin-right: 10px;">
        <option value="">Tous les projets</option>
        <?php foreach($projects as $p): ?>
            <option value="<?php echo htmlspecialchars($p['name']); ?>">
                <?php echo htmlspecialchars($p['name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select id="filter-status" style="width: 200px; display: inline-block;">
        <option value="">Tous les statuts</option>
        <option value="Nouveau">Nouveau</option>
        <option value="En cours">En cours</option>
        <option value="En attente">En attente</option>
        <option value="À valider">À valider</option>
    </select>
</div>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Projet</th>
                        <th>Type</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
        <?php foreach($tickets as $ticket): ?>
        <tr>
        <td>#<?php echo $ticket['id']; ?></td>
        <td><?php echo htmlspecialchars($ticket['title']); ?></td>
        <td><?php echo htmlspecialchars($ticket['project']); ?></td>
        <td>
                <span class="badge type-<?php echo $ticket['type']; ?>">
                    <?php echo $ticket['type_label']; ?>
                </span>
            </td>
            <td>
                <span class="badge status-<?php echo $ticket['status']; ?>">
                    <?php echo $ticket['status_label']; ?>
                </span>
            </td>
            <td><a href="ticket-detail.php?id=<?php echo $ticket['id']; ?>">Voir</a></td>
            </tr>
                <?php endforeach; ?>
            </tbody>
            </table>
        </div>
    </main>
    <script src="js/app.js"></script>
</body>
</html>
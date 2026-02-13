<?php
$message = "";
// Simulation de mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Si c'est le formulaire de mot de passe
    if (isset($_POST['new_password'])) {
        $message = '<div class="alert alert-success">✅ Mot de passe mis à jour avec succès !</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <aside class="sidebar">
        <h2>Ticketing App</h2>
        <nav>
            <ul>
                <li><a href="dashboard.php">Tableau de bord</a></li>
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
            <h1>Mon Profil</h1>
        </header>

        <?php if ($message)
            echo $message; ?>

        <div class="grid-2">
            <div class="card">
                <h3>Mes informations</h3>
                <div style="text-align: center; margin: 20px 0;">
                    <div
                        style="width: 80px; height: 80px; background: #2c3e50; border-radius: 50%; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem;">
                        IR</div>
                </div>
                <form id="profileForm">
                    <div class="form-group">
                        <label>Nom complet</label>
                        <input type="text" value="Ilan Rubaud" disabled style="background: #f9f9f9;">
                    </div>
                    <div class="form-group">
                        <label>Rôle</label>
                        <input type="text" value="Collaborateur" disabled style="background: #f9f9f9;">
                    </div>
                </form>
            </div>

            <div class="card">
                <h3>Sécurité</h3>

                <form action="" method="POST" id="passwordForm">
                    <div class="form-group">
                        <label>Ancien mot de passe</label>
                        <input type="password" name="old_password" required>
                    </div>
                    <div class="form-group">
                        <label>Nouveau mot de passe</label>
                        <input type="password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirmer nouveau mot de passe</label>
                        <input type="password" name="confirm_password" required>
                    </div>
                    <button type="submit">Mettre à jour</button>
                </form>
            </div>
        </div>
    </main>
    <script src="js/app.js"></script>
</body>

</html>
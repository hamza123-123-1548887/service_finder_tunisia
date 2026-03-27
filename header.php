<?php if(!isset($conn)) require_once __DIR__ . '/../config.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Service Finder Tunisia' ?></title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="container nav-container">
        <a href="/" class="logo">🔧 Service Finder</a>
        <button class="nav-toggle" onclick="toggleMenu()">☰</button>
        <ul class="nav-links" id="navLinks">
            <li><a href="/">Accueil</a></li>
            <?php if (isLoggedIn()): ?>
                <li><a href="/dashboard.php">Tableau de bord</a></li>
                <?php if (getUserRole() === 'provider'): ?>
                    <li><a href="/add_service.php">Ajouter un service</a></li>
                <?php endif; ?>
                <?php if (getUserRole() === 'admin'): ?>
                    <li><a href="/admin_users.php">Utilisateurs</a></li>
                    <li><a href="/admin_services.php">Services</a></li>
                <?php endif; ?>
                <li><a href="/logout.php" class="btn btn-outline btn-sm">Déconnexion</a></li>
            <?php else: ?>
                <li><a href="/login.php" class="btn btn-outline btn-sm">Connexion</a></li>
                <li><a href="/register.php" class="btn btn-primary btn-sm">Inscription</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<main class="container">

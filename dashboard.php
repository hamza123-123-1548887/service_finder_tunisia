<?php
require_once 'config.php';
require_once 'includes/functions.php';

if (!isLoggedIn()) redirect('/login.php');

$role = getUserRole();
$pageTitle = 'Tableau de bord - Service Finder Tunisia';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h2>Bienvenue, <?= sanitize($_SESSION['user_name']) ?> 👋</h2>
    <p>Rôle : <strong><?= $role === 'client' ? 'Client' : ($role === 'provider' ? 'Prestataire' : 'Administrateur') ?></strong></p>
</div>

<?php if ($role === 'provider'): ?>
    <?php $myServices = getUserServices($conn, $_SESSION['user_id']); ?>
    <div class="stats-grid">
        <div class="stat-card"><div class="number"><?= count($myServices) ?></div><div class="label">Mes services</div></div>
    </div>
    <h3>Mes services</h3>
    <a href="/add_service.php" class="btn btn-primary" style="margin-bottom:16px;">+ Ajouter un service</a>
    <?php if (empty($myServices)): ?>
        <p>Vous n'avez pas encore ajouté de service.</p>
    <?php else: ?>
        <div class="table-responsive">
        <table>
            <tr><th>Titre</th><th>Catégorie</th><th>Ville</th><th>Prix</th><th>Actions</th></tr>
            <?php foreach ($myServices as $svc): ?>
            <tr>
                <td><?= sanitize($svc['title']) ?></td>
                <td><?= sanitize($svc['category']) ?></td>
                <td><?= sanitize($svc['city']) ?></td>
                <td><?= $svc['price'] ? number_format($svc['price'], 2) . ' TND' : 'Sur devis' ?></td>
                <td>
                    <a href="/edit_service.php?id=<?= $svc['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
                    <a href="/delete_service.php?id=<?= $svc['id'] ?>" class="btn btn-sm btn-danger confirm-delete">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        </div>
    <?php endif; ?>

<?php elseif ($role === 'admin'): ?>
    <?php
    $allUsers = getAllUsers($conn);
    $allServices = getAllServices($conn);
    ?>
    <div class="stats-grid">
        <div class="stat-card"><div class="number"><?= count($allUsers) ?></div><div class="label">Utilisateurs</div></div>
        <div class="stat-card"><div class="number"><?= count($allServices) ?></div><div class="label">Services</div></div>
    </div>
    <p><a href="/admin_users.php" class="btn btn-primary">Gérer les utilisateurs</a> <a href="/admin_services.php" class="btn btn-outline">Gérer les services</a></p>

<?php else: ?>
    <div class="stats-grid">
        <div class="stat-card"><div class="number">🔍</div><div class="label">Rechercher un service</div></div>
    </div>
    <p>Utilisez la <a href="/">page d'accueil</a> pour rechercher des prestataires par ville et catégorie.</p>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

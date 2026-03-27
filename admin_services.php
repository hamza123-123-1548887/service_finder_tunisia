<?php
require_once 'config.php';
require_once 'includes/functions.php';

if (!isLoggedIn() || getUserRole() !== 'admin') redirect('/dashboard.php');

$services = getAllServices($conn);
$pageTitle = 'Gestion des services - Service Finder Tunisia';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h2>Gestion des services</h2>
    <p><a href="/dashboard.php">← Tableau de bord</a></p>
</div>

<div class="table-responsive">
<table>
    <tr><th>ID</th><th>Titre</th><th>Prestataire</th><th>Catégorie</th><th>Ville</th><th>Prix</th><th>Actions</th></tr>
    <?php foreach ($services as $svc): ?>
    <tr>
        <td><?= $svc['id'] ?></td>
        <td><?= sanitize($svc['title']) ?></td>
        <td><?= sanitize($svc['provider_name']) ?></td>
        <td><?= sanitize($svc['category']) ?></td>
        <td><?= sanitize($svc['city']) ?></td>
        <td><?= $svc['price'] ? number_format($svc['price'], 2) . ' TND' : 'Sur devis' ?></td>
        <td>
            <a href="/delete_service.php?id=<?= $svc['id'] ?>" class="btn btn-sm btn-danger confirm-delete">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

<?php include 'includes/footer.php'; ?>

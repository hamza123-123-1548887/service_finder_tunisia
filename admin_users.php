<?php
require_once 'config.php';
require_once 'includes/functions.php';

if (!isLoggedIn() || getUserRole() !== 'admin') redirect('/dashboard.php');

if (isset($_GET['delete'])) {
    $delId = intval($_GET['delete']);
    if ($delId != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $delId);
        $stmt->execute();
    }
    redirect('/admin_users.php');
}

$users = getAllUsers($conn);
$pageTitle = 'Gestion des utilisateurs - Service Finder Tunisia';
include 'includes/header.php';
?>

<div class="dashboard-header">
    <h2>Gestion des utilisateurs</h2>
    <p><a href="/dashboard.php">← Tableau de bord</a></p>
</div>

<div class="table-responsive">
<table>
    <tr><th>ID</th><th>Nom</th><th>Email</th><th>Rôle</th><th>Inscrit le</th><th>Actions</th></tr>
    <?php foreach ($users as $u): ?>
    <tr>
        <td><?= $u['id'] ?></td>
        <td><?= sanitize($u['name']) ?></td>
        <td><?= sanitize($u['email']) ?></td>
        <td><?= $u['role'] ?></td>
        <td><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
        <td>
            <?php if ($u['id'] != $_SESSION['user_id']): ?>
                <a href="/admin_users.php?delete=<?= $u['id'] ?>" class="btn btn-sm btn-danger confirm-delete">Supprimer</a>
            <?php else: ?>
                <em>Vous</em>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
</div>

<?php include 'includes/footer.php'; ?>

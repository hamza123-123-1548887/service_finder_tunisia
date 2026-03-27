<?php
require_once 'config.php';
require_once 'includes/functions.php';

if (isLoggedIn()) redirect('/dashboard.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? 'client';

    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse email invalide.';
    } elseif (strlen($password) < 6) {
        $error = 'Le mot de passe doit contenir au moins 6 caractères.';
    } elseif ($password !== $confirm) {
        $error = 'Les mots de passe ne correspondent pas.';
    } elseif (!in_array($role, ['client', 'provider'])) {
        $error = 'Rôle invalide.';
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $error = 'Cet email est déjà utilisé.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
            if ($stmt->execute()) {
                $success = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
            } else {
                $error = 'Erreur lors de l\'inscription.';
            }
        }
    }
}

$pageTitle = 'Inscription - Service Finder Tunisia';
include 'includes/header.php';
?>

<div class="form-container">
    <h2>Inscription</h2>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Nom complet</label>
            <input type="text" name="name" value="<?= sanitize($_POST['name'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= sanitize($_POST['email'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" required minlength="6">
        </div>
        <div class="form-group">
            <label>Confirmer le mot de passe</label>
            <input type="password" name="confirm_password" required>
        </div>
        <div class="form-group">
            <label>Vous êtes :</label>
            <select name="role">
                <option value="client">Client (je cherche un service)</option>
                <option value="provider">Prestataire (j'offre un service)</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%">S'inscrire</button>
    </form>
    <p style="text-align:center; margin-top:16px;">Déjà inscrit ? <a href="/login.php">Se connecter</a></p>
</div>

<?php include 'includes/footer.php'; ?>

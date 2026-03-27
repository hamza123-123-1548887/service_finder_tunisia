<?php
require_once 'config.php';

if (isLoggedIn()) redirect('/dashboard.php');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $error = 'Veuillez remplir tous les champs.';
    } else {
        $stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            redirect('/dashboard.php');
        } else {
            $error = 'Email ou mot de passe incorrect.';
        }
    }
}

$pageTitle = 'Connexion - Service Finder Tunisia';
include 'includes/header.php';
?>

<div class="form-container">
    <h2>Connexion</h2>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <form method="POST">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= sanitize($_POST['email'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%">Se connecter</button>
    </form>
    <p style="text-align:center; margin-top:16px;">Pas encore inscrit ? <a href="/register.php">S'inscrire</a></p>
</div>

<?php include 'includes/footer.php'; ?>

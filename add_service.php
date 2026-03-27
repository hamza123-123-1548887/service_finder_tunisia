<?php
require_once 'config.php';
require_once 'includes/functions.php';

if (!isLoggedIn() || getUserRole() !== 'provider') redirect('/dashboard.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = $_POST['category'] ?? '';
    $city = $_POST['city'] ?? '';
    $phone = trim($_POST['phone'] ?? '');
    $price = $_POST['price'] !== '' ? floatval($_POST['price']) : null;

    if (empty($title) || empty($category) || empty($city)) {
        $error = 'Titre, catégorie et ville sont obligatoires.';
    } else {
        $image = null;
        if (!empty($_FILES['image']['name'])) {
            $image = uploadImage($_FILES['image']);
            if (!$image) $error = 'Image invalide (formats: JPG, PNG, GIF, WebP, max 5MB).';
        }

        if (!$error) {
            $userId = $_SESSION['user_id'];
            $stmt = $conn->prepare("INSERT INTO services (user_id, title, description, category, city, phone, price, image) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssssds", $userId, $title, $description, $category, $city, $phone, $price, $image);
            if ($stmt->execute()) {
                $success = 'Service ajouté avec succès !';
            } else {
                $error = 'Erreur lors de l\'ajout.';
            }
        }
    }
}

$pageTitle = 'Ajouter un service - Service Finder Tunisia';
include 'includes/header.php';
?>

<div class="form-container" style="max-width:600px;">
    <h2>Ajouter un service</h2>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Titre du service *</label>
            <input type="text" name="title" value="<?= sanitize($_POST['title'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description"><?= sanitize($_POST['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Catégorie *</label>
            <select name="category" required>
                <option value="">Choisir...</option>
                <?php foreach (getCategories() as $cat): ?>
                    <option value="<?= $cat ?>" <?= ($_POST['category'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Ville *</label>
            <select name="city" required>
                <option value="">Choisir...</option>
                <?php foreach (getCities() as $c): ?>
                    <option value="<?= $c ?>" <?= ($_POST['city'] ?? '') === $c ? 'selected' : '' ?>><?= $c ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Téléphone</label>
            <input type="text" name="phone" value="<?= sanitize($_POST['phone'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Prix (TND)</label>
            <input type="number" name="price" step="0.01" min="0" value="<?= sanitize($_POST['price'] ?? '') ?>" placeholder="Laisser vide = Sur devis">
        </div>
        <div class="form-group">
            <label>Image</label>
            <input type="file" name="image" accept="image/*">
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%">Ajouter</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>

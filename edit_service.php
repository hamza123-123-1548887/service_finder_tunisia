<?php
require_once 'config.php';
require_once 'includes/functions.php';

if (!isLoggedIn() || getUserRole() !== 'provider') redirect('/dashboard.php');

$id = intval($_GET['id'] ?? 0);
$service = getServiceById($conn, $id);

if (!$service || $service['user_id'] != $_SESSION['user_id']) {
    redirect('/dashboard.php');
}

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
        $image = $service['image'];
        if (!empty($_FILES['image']['name'])) {
            $newImage = uploadImage($_FILES['image']);
            if ($newImage) {
                if ($image && file_exists("uploads/$image")) unlink("uploads/$image");
                $image = $newImage;
            } else {
                $error = 'Image invalide.';
            }
        }

        if (!$error) {
            $stmt = $conn->prepare("UPDATE services SET title=?, description=?, category=?, city=?, phone=?, price=?, image=? WHERE id=? AND user_id=?");
            $stmt->bind_param("sssssdsii", $title, $description, $category, $city, $phone, $price, $image, $id, $_SESSION['user_id']);
            if ($stmt->execute()) {
                $success = 'Service mis à jour !';
                $service = getServiceById($conn, $id);
            } else {
                $error = 'Erreur lors de la mise à jour.';
            }
        }
    }
}

$pageTitle = 'Modifier un service - Service Finder Tunisia';
include 'includes/header.php';
?>

<div class="form-container" style="max-width:600px;">
    <h2>Modifier le service</h2>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Titre *</label>
            <input type="text" name="title" value="<?= sanitize($service['title']) ?>" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description"><?= sanitize($service['description']) ?></textarea>
        </div>
        <div class="form-group">
            <label>Catégorie *</label>
            <select name="category" required>
                <?php foreach (getCategories() as $cat): ?>
                    <option value="<?= $cat ?>" <?= $service['category'] === $cat ? 'selected' : '' ?>><?= $cat ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Ville *</label>
            <select name="city" required>
                <?php foreach (getCities() as $c): ?>
                    <option value="<?= $c ?>" <?= $service['city'] === $c ? 'selected' : '' ?>><?= $c ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Téléphone</label>
            <input type="text" name="phone" value="<?= sanitize($service['phone']) ?>">
        </div>
        <div class="form-group">
            <label>Prix (TND)</label>
            <input type="number" name="price" step="0.01" min="0" value="<?= $service['price'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label>Image <?= $service['image'] ? '(actuelle conservée si vide)' : '' ?></label>
            <input type="file" name="image" accept="image/*">
            <?php if ($service['image']): ?>
                <img src="/uploads/<?= sanitize($service['image']) ?>" style="max-width:200px;margin-top:8px;border-radius:8px;">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary" style="width:100%">Enregistrer</button>
    </form>
    <p style="text-align:center;margin-top:16px;"><a href="/dashboard.php">← Retour au tableau de bord</a></p>
</div>

<?php include 'includes/footer.php'; ?>

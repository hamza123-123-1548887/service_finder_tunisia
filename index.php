<?php
require_once 'config.php';
require_once 'includes/functions.php';

$city = $_GET['city'] ?? '';
$category = $_GET['category'] ?? '';
$services = getServices($conn, $city, $category);

$pageTitle = 'Service Finder Tunisia - Accueil';
include 'includes/header.php';
?>

<div class="hero">
    <h1>Trouvez le bon prestataire en Tunisie</h1>
    <p>Plombiers, électriciens, techniciens... Recherchez par ville et catégorie</p>
    <form class="search-form" method="GET" action="/">
        <select name="city">
            <option value="">Toutes les villes</option>
            <?php foreach (getCities() as $c): ?>
                <option value="<?= $c ?>" <?= $city === $c ? 'selected' : '' ?>><?= $c ?></option>
            <?php endforeach; ?>
        </select>
        <select name="category">
            <option value="">Toutes les catégories</option>
            <?php foreach (getCategories() as $cat): ?>
                <option value="<?= $cat ?>" <?= $category === $cat ? 'selected' : '' ?>><?= $cat ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Rechercher</button>
    </form>
</div>

<h2>Services disponibles <?= !empty($city) || !empty($category) ? '(filtrés)' : '' ?></h2>

<?php if (empty($services)): ?>
    <p style="text-align:center; padding:40px; color:var(--secondary);">Aucun service trouvé. Essayez une autre recherche.</p>
<?php else: ?>
    <div class="services-grid">
        <?php foreach ($services as $svc): ?>
            <div class="service-card">
                <?php if ($svc['image']): ?>
                    <img src="/uploads/<?= sanitize($svc['image']) ?>" alt="<?= sanitize($svc['title']) ?>">
                <?php else: ?>
                    <div class="no-image">🔧</div>
                <?php endif; ?>
                <div class="card-body">
                    <span class="category"><?= sanitize($svc['category']) ?></span>
                    <h3><?= sanitize($svc['title']) ?></h3>
                    <p class="meta">📍 <?= sanitize($svc['city']) ?> · 👤 <?= sanitize($svc['provider_name']) ?></p>
                    <?php if ($svc['phone']): ?>
                        <p class="meta">📞 <?= sanitize($svc['phone']) ?></p>
                    <?php endif; ?>
                    <p class="price"><?= $svc['price'] ? number_format($svc['price'], 2) . ' TND' : 'Sur devis' ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>

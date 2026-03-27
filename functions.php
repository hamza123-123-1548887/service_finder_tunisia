<?php
function getServices($conn, $city = '', $category = '') {
    $sql = "SELECT s.*, u.name as provider_name FROM services s JOIN users u ON s.user_id = u.id WHERE 1=1";
    $params = [];
    $types = '';

    if (!empty($city)) {
        $sql .= " AND s.city LIKE ?";
        $params[] = "%$city%";
        $types .= 's';
    }
    if (!empty($category)) {
        $sql .= " AND s.category LIKE ?";
        $params[] = "%$category%";
        $types .= 's';
    }

    $sql .= " ORDER BY s.created_at DESC";
    $stmt = $conn->prepare($sql);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getServiceById($conn, $id) {
    $stmt = $conn->prepare("SELECT s.*, u.name as provider_name FROM services s JOIN users u ON s.user_id = u.id WHERE s.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

function getUserServices($conn, $userId) {
    $stmt = $conn->prepare("SELECT * FROM services WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

function getAllUsers($conn) {
    return $conn->query("SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC")->fetch_all(MYSQLI_ASSOC);
}

function getAllServices($conn) {
    return $conn->query("SELECT s.*, u.name as provider_name FROM services s JOIN users u ON s.user_id = u.id ORDER BY s.created_at DESC")->fetch_all(MYSQLI_ASSOC);
}

function uploadImage($file) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $maxSize = 5 * 1024 * 1024; // 5MB

    if ($file['error'] !== UPLOAD_ERR_OK) return null;
    if (!in_array($file['type'], $allowedTypes)) return null;
    if ($file['size'] > $maxSize) return null;

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('svc_') . '.' . $ext;
    $destination = __DIR__ . '/../uploads/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return $filename;
    }
    return null;
}

function getCities() {
    return ['Tunis', 'Sfax', 'Sousse', 'Kairouan', 'Bizerte', 'Gabès', 'Ariana', 'Gafsa', 'Monastir', 'Ben Arous', 'Kasserine', 'Médenine', 'Nabeul', 'Tataouine', 'Béja', 'Jendouba', 'Mahdia', 'Sidi Bouzid', 'Le Kef', 'Tozeur', 'Siliana', 'Kébili', 'Zaghouan', 'Manouba'];
}

function getCategories() {
    return ['Plomberie', 'Électricité', 'Peinture', 'Climatisation', 'Menuiserie', 'Maçonnerie', 'Jardinage', 'Nettoyage', 'Réparation électroménager', 'Déménagement', 'Soudure', 'Carrelage', 'Autre'];
}
?>

<?php
require_once 'config.php';

if (!isLoggedIn()) redirect('/login.php');

$id = intval($_GET['id'] ?? 0);
$role = getUserRole();

if ($role === 'provider') {
    $stmt = $conn->prepare("SELECT image FROM services WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $_SESSION['user_id']);
} elseif ($role === 'admin') {
    $stmt = $conn->prepare("SELECT image FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
} else {
    redirect('/dashboard.php');
}

$stmt->execute();
$service = $stmt->get_result()->fetch_assoc();

if ($service) {
    if ($service['image'] && file_exists("uploads/" . $service['image'])) {
        unlink("uploads/" . $service['image']);
    }
    if ($role === 'admin') {
        $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
        $stmt->bind_param("i", $id);
    } else {
        $stmt = $conn->prepare("DELETE FROM services WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $id, $_SESSION['user_id']);
    }
    $stmt->execute();
}

redirect($role === 'admin' ? '/admin_services.php' : '/dashboard.php');
?>

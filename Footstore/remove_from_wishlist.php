<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    require_once 'pdo.php';
    $sql = "SELECT * FROM products WHERE product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch();

    if ($product && isset($_SESSION['wishlist'])) {
        foreach ($_SESSION['wishlist'] as $key => $item) {
            if ($item['product_id'] === $product_id) {
                unset($_SESSION['wishlist'][$key]);
                $_SESSION['wishlist'] = array_values($_SESSION['wishlist']); // Reindex array
                echo json_encode(['success' => true, 'wishlist_count' => count($_SESSION['wishlist'])]);
                exit;
            }
        }
    }
}

echo json_encode(['success' => false]);
exit;
?>
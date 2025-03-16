<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['productId'])) {
    $productId = $_GET['productId'];

    if (!isset($_SESSION['cart'])) {
        echo json_encode(['success' => false, 'message' => 'Cart is empty']);
        exit;
    }

    $found = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['product_id'] === $productId) {
            unset($_SESSION['cart'][$key]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindex array
            $found = true;
            break;
        }
    }

    if ($found) {
        echo json_encode(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
        exit;
    } else {
        echo json_encode(['success' => false, 'message' => 'Product not found in cart']);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Invalid request']);
exit;
?>
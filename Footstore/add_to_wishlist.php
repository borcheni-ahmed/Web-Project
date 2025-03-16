<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $product_id = $data['product_id'];

    require_once 'pdo.php';
    $sql = "SELECT * FROM products WHERE product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch();

    if ($product) {
        if (!isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = [];
        }

        $found = false;
        foreach ($_SESSION['wishlist'] as &$item) {
            if ($item['product_id'] === $product_id) {
                $found = true;
                break;
            }
        }

        if (!$found) {
            $_SESSION['wishlist'][] = [
                'product_id' => $product['product_id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image_url' => $product['image_url']
            ];
        }

        echo json_encode(['success' => true]);
        exit;
    }
}

echo json_encode(['success' => false]);
exit;

?>

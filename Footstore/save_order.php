<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once 'pdo.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: bag.php');
    exit;
}

$user_id = $_SESSION['user']['user_id']; // Assuming user_id is stored in the session
$total_amount = 0;

foreach ($_SESSION['cart'] as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}

// Insert the order into the orders table
$sql = "INSERT INTO orders (user_id, total_amount, status) VALUES (:user_id, :total_amount, 'pending')";
$stmt = $pdo->prepare($sql);
$stmt->execute([
    ':user_id' => $user_id,
    ':total_amount' => $total_amount
]);

$order_id = $pdo->lastInsertId();

// Redirect to facture.php with the order ID
header("Location: facture.php?order_id=$order_id");
exit;
?>
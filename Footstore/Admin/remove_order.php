<?php
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Include your database connection file
require_once '../pdo.php';

// Check if the product ID is provided in the URL
if (!isset($_GET['id'])) {
    $_SESSION['error'] = "order ID not provided.";
    header('Location: order.php');
    exit;
}

$order_id = $_GET['id'];

try {
    // Prepare the SQL query to delete the product
    $sql = "DELETE FROM orders WHERE order_id = :order_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':order_id' => $order_id]);

    // Set success message and redirect
    $_SESSION['message'] = "order removed successfully.";
    header('Location: order.php');
    exit;
} catch (PDOException $e) {
    // Handle database errors
    $_SESSION['error'] = "Error removing order: " . $e->getMessage();
    header('Location: order.php');
    exit;
}
?>
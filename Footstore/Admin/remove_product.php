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
    $_SESSION['error'] = "Product ID not provided.";
    header('Location: product.php');
    exit;
}

$product_id = $_GET['id'];

try {
    // Prepare the SQL query to delete the product
    $sql = "DELETE FROM products WHERE product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':product_id' => $product_id]);

    // Set success message and redirect
    $_SESSION['message'] = "Product removed successfully.";
    header('Location: product.php');
    exit;
} catch (PDOException $e) {
    // Handle database errors
    $_SESSION['error'] = "Error removing product: " . $e->getMessage();
    header('Location: product.php');
    exit;
}
?>
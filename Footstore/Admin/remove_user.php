<?php
session_start();

if (!isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Include your database connection file
require_once '../pdo.php';

// Check if the user ID is provided in the URL
if (!isset($_GET['id'])) {
    header('Location: User.php'); // Redirect to the users page if no ID is provided
    exit;
}

$user_id = $_GET['id'];

try {
    // Prepare the SQL query to delete the user
    $sql = "DELETE FROM users WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':user_id' => $user_id]);

    // Redirect back to the users page with a success message
    $_SESSION['message'] = "User removed successfully.";
    header('Location: User.php');
    exit;
} catch (PDOException $e) {
    // Handle any errors
    $_SESSION['error'] = "Error removing user: " . $e->getMessage();
    header('Location: User.php');
    exit;
}
?>
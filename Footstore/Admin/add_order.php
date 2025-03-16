<?php
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Include your database connection file
require_once '../pdo.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];
    $total_amount = $_POST['total_amount'];

    // Validate input (you can add more validation as needed)
    if (empty($user_id) || empty($status) || empty($total_amount)) {
        $_SESSION['error'] = "All fields are required.";
        header('Location: Formulaire_Add_order.html');
        exit;
    }

    try {
        // Insert the new order into the database
        $sql = "INSERT INTO orders (user_id, status, total_amount) VALUES (:user_id, :status, :total_amount)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':user_id' => $user_id,
            ':status' => $status,
            ':total_amount' => $total_amount
        ]);

        // Set success message and redirect
        $_SESSION['message'] = "Order added successfully.";
        header('Location: order.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error adding order: " . $e->getMessage();
        header('Location: Formulaire_Add_order.html');
        exit;
    }
}

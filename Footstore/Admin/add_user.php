<?php
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['user']) && $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Include your database connection file
require_once '../pdo.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];

    // Validate input
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($first_name) || empty($last_name)) {
        $_SESSION['error'] = "All fields are required.";
        echo "All fields are required.";
        header('Location: Formulaire_Add_user.html');
        exit;
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match.";
        echo "Passwords do not match.";
        header('Location: Formulaire_Add_user.html');
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Insert the new user into the database
        $sql = "INSERT INTO users (username, email, password_hash, first_name, last_name, role) VALUES (:username, :email, :password, :first_name, :last_name, 'user')";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashed_password,
            ':first_name' => $first_name,
            ':last_name' => $last_name
        ]);

        // Set success message and redirect
        $_SESSION['message'] = "User created successfully.";
        header('Location: User.php');
        exit;
    } catch (PDOException $e) {
        // Handle database errors
        $_SESSION['error'] = "Error creating user: " . $e->getMessage();
        echo "Error creating user: " . $e->getMessage();
        header('Location: Formulaire_Add_user.html');
        exit;
    }
} else {
    // Redirect if the form is not submitted
    header('Location: Formulaire_Add_user.html');
    exit;
}

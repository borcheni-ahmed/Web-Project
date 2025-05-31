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
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock'];
    $category_id = $_POST['category'];
    $collections = $_POST['collections'];

    // Handle file upload
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/images'; // Directory to store uploaded images
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true); // Create the directory if it doesn't exist
        }

        $file_name = basename($_FILES['image']['name']);
        $file_path = $upload_dir . $file_name;

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
            $image_url = $file_path;
        } else {
            $_SESSION['error'] = "Failed to upload image.";
            header('Location: Formulaire_Add_product.html');
            exit;
        }
    }

    if (empty($name) || empty($description) || empty($price) || empty($stock_quantity) || empty($category_id) || empty($collections) || empty($image_url)) {
        $_SESSION['error'] = "All fields are required.";
        header('Location: Formulaire_Add_product.html');
        exit;
    }

    try {
        $sql = "INSERT INTO products (name, description, price, stock_quantity, category_id, collections, image_url) VALUES (:name, :description, :price, :stock_quantity, :category_id, :collections, :image_url)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':description' => $description,
            ':price' => $price,
            ':stock_quantity' => $stock_quantity,
            ':category_id' => $category_id,
            ':collections' => $collections,
            ':image_url' => $image_url
        ]);

        $_SESSION['message'] = "Product added successfully.";
        header('Location: product.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error adding product: " . $e->getMessage();
        header('Location: Formulaire_Add_product.html');
        exit;
    }
} else {
    header('Location: Formulaire_Add_product.html');
    exit;
}
?>
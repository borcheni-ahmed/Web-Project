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
  header('Location: products.php');
  exit;
}

$product_id = $_GET['id'];

// Fetch the product's current data
try {
  $sql = "SELECT * FROM products WHERE product_id = :product_id";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([':product_id' => $product_id]);
  $product = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$product) {
    $_SESSION['error'] = "Product not found.";
    header('Location: products.php');
    exit;
  }
} catch (PDOException $e) {
  $_SESSION['error'] = "Error fetching product data: " . $e->getMessage();
  header('Location: products.php');
  exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Retrieve form data
  $name = $_POST['name'];
  $description = $_POST['description'];
  $price = $_POST['price'];
  $stock_quantity = $_POST['stock'];
  $category_id = $_POST['category'];
  $collections = $_POST['collections'];

  // Validate input (you can add more validation as needed)
  if (empty($name) || empty($description) || empty($price) || empty($stock_quantity) || empty($category_id) || empty($collections)) {
    $_SESSION['error'] = "All fields are required.";
  } else {
    try {
      // Update the product in the database
      $sql = "UPDATE products SET name = :name, description = :description, price = :price, stock_quantity = :stock_quantity, category_id = :category_id, collections = :collections WHERE product_id = :product_id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':name' => $name,
        ':description' => $description,
        ':price' => $price,
        ':stock_quantity' => $stock_quantity,
        ':category_id' => $category_id,
        ':collections' => $collections,
        ':product_id' => $product_id
      ]);

      // Set success message and redirect
      $_SESSION['message'] = "Product updated successfully.";
      header('Location: products.php');
      exit;
    } catch (PDOException $e) {
      $_SESSION['error'] = "Error updating product: " . $e->getMessage();
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Update Product</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .form-container {
      background: linear-gradient(135deg, #f7fafc, #edf2f7);
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-input {
      transition: all 0.3s ease;
      padding: 0.75rem 1rem;
      font-size: 1rem;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      width: 100%;
    }

    .form-input:focus {
      border-color: #ed8936;
      box-shadow: 0 0 0 3px rgba(237, 137, 54, 0.2);
    }

    .form-button {
      background-color: #ed8936;
      color: white;
      transition: all 0.3s ease;
      padding: 0.75rem 1.5rem;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 500;
    }

    .form-button:hover {
      background-color: #dd6b20;
      transform: translateY(-2px);
    }

    .form-button:active {
      transform: translateY(0);
    }

    /* Custom file upload button */
    .file-upload {
      position: relative;
      overflow: hidden;
      display: inline-block;
      width: 100%;
    }

    .file-upload input[type="file"] {
      font-size: 1rem;
      position: absolute;
      left: 0;
      top: 0;
      opacity: 0;
      width: 100%;
      height: 100%;
      cursor: pointer;
    }

    .file-upload-label {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0.75rem 1rem;
      background-color: #ed8936;
      color: white;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 500;
      transition: background-color 0.3s ease;
      cursor: pointer;
    }

    .file-upload-label:hover {
      background-color: #dd6b20;
    }

    .file-upload-label svg {
      margin-right: 0.5rem;
    }
  </style>
</head>

<body class="h-screen overflow-hidden" style="background: #edf2f7">
  <div>
    <script
      src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js"
      defer></script>

    <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-200">
      <!-- Sidebar Overlay -->
      <div
        :class="sidebarOpen ? 'block' : 'hidden'"
        @click="sidebarOpen = false"
        class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 lg:hidden"></div>

      <!-- Sidebar -->
      <div
        :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
        class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-gray-900 lg:translate-x-0 lg:static lg:inset-0">
        <div class="flex items-center justify-center mt-8">
          <div class="flex items-center">
            <svg
              class="w-12 h-12 text-orange-500"
              viewBox="0 0 512 512"
              fill="currentColor"
              xmlns="http://www.w3.org/2000/svg">
              <path
                d="M364.61 390.213C304.625 450.196 207.37 450.196 147.386 390.213C117.394 360.22 102.398 320.911 102.398 281.6C102.398 242.291 117.394 202.981 147.386 172.989C147.386 230.4 153.6 281.6 230.4 307.2C230.4 256 256 102.4 294.4 76.7999C320 128 334.618 142.997 364.608 172.989C394.601 202.981 409.597 242.291 409.597 281.6C409.597 320.911 394.601 360.22 364.61 390.213Z"></path>
              <path
                d="M201.694 387.105C231.686 417.098 280.312 417.098 310.305 387.105C325.301 372.109 332.8 352.456 332.8 332.8C332.8 313.144 325.301 293.491 310.305 278.495C295.309 263.498 288 256 275.2 230.4C256 243.2 243.201 320 243.201 345.6C201.694 345.6 179.2 332.8 179.2 332.8C179.2 352.456 186.698 372.109 201.694 387.105Z"
                fill="white"></path>
            </svg>
            <span class="mx-2 text-2xl font-semibold text-white">Dashboard</span>
          </div>
        </div>

        <nav class="mt-10">
          <!-- Dashboard Link -->
          <a
            class="flex items-center px-6 py-2 mt-4 text-gray-100 bg-gray-700 bg-opacity-25 transform hover:rotate-3 hover:scale-110 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100 transition-all duration-300 ease-in-out ml-0"
            href="component.html">
            <svg
              class="w-6 h-6"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor">
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
            </svg>
            <span class="mx-3">Dashboard</span>
          </a>

          <!-- Users Link with active state -->
          <a
            class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100 text-xl transform hover:rotate-3 hover:scale-110 transition-all duration-300 ease-in-out ml-4 active:scale-95 active:bg-gray-800"
            href="User.php">
            <span class="mx-3">Users</span>
          </a>

          <!-- Product Link -->
          <a
            class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100 text-xl transform hover:rotate-3 hover:scale-110 transition-all duration-300 ease-in-out ml-8"
            href="product.php">
            <span class="mx-3">Product</span>
          </a>

          <!-- Order Link -->
          <a
            class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100 text-xl transform hover:rotate-3 hover:scale-110 transition-all duration-300 ease-in-out ml-12"
            href="order.php">
            <span class="mx-3">Order</span>
          </a>

         
        </nav>
      </div>

      <!-- Main Content -->
      <div class="flex flex-col flex-1 overflow-hidden">
        <header
          class="flex items-center justify-between px-6 py-4 bg-white border-b-4 border-orange-600">
          <div class="flex items-center">
            <button
              @click="sidebarOpen = true"
              class="text-gray-500 focus:outline-none lg:hidden">
              <svg
                class="w-6 h-6"
                viewBox="0 0 24 24"
                fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M4 6H20M4 12H20M4 18H11"
                  stroke="currentColor"
                  stroke-width="2"
                  stroke-linecap="round"
                  stroke-linejoin="round"></path>
              </svg>
            </button>

            <div class="relative mx-4 lg:mx-0">
              <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg
                  class="w-5 h-5 text-gray-500"
                  viewBox="0 0 24 24"
                  fill="none">
                  <path
                    d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"></path>
                </svg>
              </span>
              <input
                class="w-32 pl-10 pr-4 rounded-md form-input sm:w-64 focus:border-indigo-600"
                type="text"
                placeholder="Search" />
            </div>
          </div>

          <div class="flex items-center">
            <div x-data="{ dropdownOpen: false }" class="relative">
              <button
                @click="dropdownOpen = ! dropdownOpen"
                class="relative block w-8 h-8 overflow-hidden rounded-full shadow focus:outline-none">
                <img
                  class="object-cover w-full h-full"
                  src="https://images.unsplash.com/photo-1528892952291-009c663ce843?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=296&amp;q=80"
                  alt="Your avatar" />
              </button>
            </div>
          </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
          <div class="container px-6 py-8 mx-auto">
            <div class="max-w-2xl mx-auto form-container p-8">
              <h2 class="text-3xl font-semibold text-gray-800 mb-6">Update Product</h2>

              <form action="Formulaire_Update_product.php?id=<?= $product_id ?>" method="POST">
                <!-- Product Name -->
                <div class="mb-6">
                  <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                  <input type="text" id="name" name="name" class="mt-1 block w-full form-input" placeholder="Enter product name" value="<?= htmlspecialchars($product['name']) ?>" required />
                </div>

                <!-- Description -->
                <div class="mb-6">
                  <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                  <textarea id="description" name="description" rows="3" class="mt-1 block w-full form-input" placeholder="Enter product description" required><?= htmlspecialchars($product['description']) ?></textarea>
                </div>

                <!-- Price -->
                <div class="mb-6">
                  <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                  <input type="number" id="price" name="price" class="mt-1 block w-full form-input" placeholder="Enter price" value="<?= htmlspecialchars($product['price']) ?>" required />
                </div>

                <!-- Stock Quantity -->
                <div class="mb-6">
                  <label for="stock" class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                  <input type="number" id="stock" name="stock" class="mt-1 block w-full form-input" placeholder="Enter stock quantity" value="<?= htmlspecialchars($product['stock_quantity']) ?>" required />
                </div>

                <!-- Category ID -->
                <div class="mb-6">
                  <label for="category" class="block text-sm font-medium text-gray-700">Category ID</label>
                  <input type="text" id="category" name="category" class="mt-1 block w-full form-input" placeholder="Enter category ID" value="<?= htmlspecialchars($product['category_id']) ?>" required />
                </div>

                <!-- Collections -->
                <div class="mb-6">
                  <label for="collections" class="block text-sm font-medium text-gray-700">Collections</label>
                  <input type="text" id="collections" name="collections" class="mt-1 block w-full form-input" placeholder="Enter collections" value="<?= htmlspecialchars($product['Collections']) ?>" required />
                </div>

                <!-- Image Upload -->
                <div class="mb-6">
                  <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                  <div class="file-upload">
                    <input type="file" id="image" name="image" class="hidden" accept="image/*" />
                    <label for="image" class="file-upload-label">
                      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                      </svg>
                      Upload Image
                    </label>
                  </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                  <button type="submit" class="form-button px-6 py-2 rounded-md shadow-md hover:shadow-lg">
                    Update Product
                  </button>
                </div>
              </form>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
</body>

</html>
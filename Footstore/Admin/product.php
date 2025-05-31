<?php
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php');
    exit;
}

// Include your database connection file
require_once '../pdo.php';

// Pagination settings
$itemsPerPage = 5; // Number of products per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page number
$offset = ($page - 1) * $itemsPerPage; // Offset for SQL query

// Search functionality
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchCondition = $searchQuery ? "WHERE name LIKE :searchQuery" : "";

// Fetch total number of products for pagination
try {
    $sqlTotal = "SELECT COUNT(*) AS total FROM products $searchCondition";
    $stmtTotal = $pdo->prepare($sqlTotal);
    if ($searchQuery) {
        $stmtTotal->execute([':searchQuery' => "%$searchQuery%"]);
    } else {
        $stmtTotal->execute();
    }
    $totalProducts = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching total products: " . $e->getMessage();
    $totalProducts = 0;
}

// Calculate total pages
$totalPages = ceil($totalProducts / $itemsPerPage);

// Fetch products for the current page
try {
    $sql = "SELECT * FROM products $searchCondition LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $itemsPerPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    if ($searchQuery) {
        $stmt->bindValue(':searchQuery', "%$searchQuery%", PDO::PARAM_STR);
    }
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching products: " . $e->getMessage();
    $products = []; // Default to an empty array if there's an error
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // JavaScript to handle search on input change
        function handleSearch() {
            const searchQuery = document.getElementById('searchInput').value;
            window.location.href = `product.php?search=${encodeURIComponent(searchQuery)}`;
        }
    </script>
</head>

<body class="h-screen overflow-hidden" style="background: #edf2f7;">
    <div>
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

        <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-200">
            <!-- Sidebar -->
            <div :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
                class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform bg-gray-900 lg:translate-x-0 lg:static lg:inset-0">
                <div class="flex items-center justify-center mt-8">
                    <div class="flex items-center">
                        <svg class="w-12 h-12 text-orange-500" viewBox="0 0 512 512" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M364.61 390.213C304.625 450.196 207.37 450.196 147.386 390.213C117.394 360.22 102.398 320.911 102.398 281.6C102.398 242.291 117.394 202.981 147.386 172.989C147.386 230.4 153.6 281.6 230.4 307.2C230.4 256 256 102.4 294.4 76.7999C320 128 334.618 142.997 364.608 172.989C394.601 202.981 409.597 242.291 409.597 281.6C409.597 320.911 394.601 360.22 364.61 390.213Z">
                            </path>
                            <path
                                d="M201.694 387.105C231.686 417.098 280.312 417.098 310.305 387.105C325.301 372.109 332.8 352.456 332.8 332.8C332.8 313.144 325.301 293.491 310.305 278.495C295.309 263.498 288 256 275.2 230.4C256 243.2 243.201 320 243.201 345.6C201.694 345.6 179.2 332.8 179.2 332.8C179.2 352.456 186.698 372.109 201.694 387.105Z"
                                fill="white"></path>
                        </svg>
                        <span class="mx-2 text-2xl font-semibold text-white">FootCap</span>
                    </div>
                </div>

                <nav class="mt-10">
                    <!-- Dashboard Link -->
                    <a class="flex items-center px-6 py-2 mt-4 text-gray-100 bg-gray-700 bg-opacity-25 transform hover:rotate-3 hover:scale-110 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100 transition-all duration-300 ease-in-out ml-0">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                        </svg>
                        <span class="mx-3">Dashboard</span>
                    </a>

                    <!-- Users Link -->
                    <a class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100 text-xl transform hover:rotate-3 hover:scale-110 transition-all duration-300 ease-in-out ml-4"
                        href="User.php">
                        <span class="mx-3">Users</span>
                    </a>

                    <!-- Product Link -->
                    <a class="flex items-center px-6 py-2 mt-4 text-gray-100 bg-gray-700 bg-opacity-25 transform hover:rotate-3 hover:scale-110 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100 transition-all duration-300 ease-in-out ml-0"
                        href="product.php">
                        <span class="mx-3">Products</span>
                    </a>

                    <!-- Order Link -->
                    <a class="flex items-center px-6 py-2 mt-4 text-gray-500 hover:bg-gray-700 hover:bg-opacity-25 hover:text-gray-100 text-xl transform hover:rotate-3 hover:scale-110 transition-all duration-300 ease-in-out ml-12"
                        href="order.php">
                        <span class="mx-3">Order</span>
                    </a>

                </nav>
            </div>

            <!-- Main Content -->
            <div class="flex flex-col flex-1 overflow-hidden">
                <header class="flex items-center justify-between px-6 py-4 bg-white border-b-4 border-orange-600">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true" class="text-gray-500 focus:outline-none lg:hidden">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </button>

                        <div class="relative mx-4 lg:mx-0">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-5 h-5 text-gray-500" viewBox="0 0 24 24" fill="none">
                                    <path
                                        d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                    </path>
                                </svg>
                            </span>
                            <input
                                id="searchInput"
                                type="text"
                                class="w-32 pl-10 pr-4 rounded-md form-input sm:w-64 focus:border-indigo-600"
                                placeholder="Search"
                                value="<?= htmlspecialchars($searchQuery) ?>"
                                onchange="handleSearch()" />
                        </div>
                    </div>

                    <div class="flex items-center">
                        <div x-data="{ notificationOpen: false }" class="relative">
                            <button @click="notificationOpen = ! notificationOpen" onclick="window.location.href='../logout.php'"
                                class="flex mx-4 text-gray-600 focus:outline-none">
                                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    </path>
                                </svg>
                            </button>

                            <div x-show="notificationOpen" @click="notificationOpen = false"
                                class="fixed inset-0 z-10 w-full h-full" style="display: none;"></div>


                        </div>


                    </div>
                </header>

                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                    <div class="container px-6 py-8 mx-auto">
                        <div class="flex items-center justify-between">
                            <!-- Products Title -->
                            <h3 class="text-3xl font-medium text-gray-700">Products</h3>

                            <!-- Add Product Button -->
                            <a href="Formulaire_Add_product.html" class="flex items-center px-4 py-2 text-white bg-orange-600 border-none hover:bg-orange-700 hover:scale-105 transform transition-all duration-300 focus:outline-none">
                                Add Product
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v14m7-7H5"></path>
                                </svg>
                            </a>
                        </div>


                        <div class="mt-8">
                            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                                <div class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg">
                                    <table class="min-w-full">
                                        <thead>
                                            <tr>
                                                <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">Product ID</th>
                                                <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">Name</th>
                                                <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">Price</th>
                                                <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">Stock Quantity</th>
                                                <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">Category ID</th>
                                                <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">Collections</th>
                                                <th class="px-6 py-3 text-xs font-medium leading-4 tracking-wider text-left text-gray-500 uppercase border-b border-gray-200 bg-gray-50">Image URL</th>
                                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                                                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50"></th>
                                            </tr>
                                        </thead>

                                        <tbody class="bg-white">
                                            <?php foreach ($products as $product): ?>
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200"><?= htmlspecialchars($product['product_id']) ?></td>
                                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200"><?= htmlspecialchars($product['name']) ?></td>
                                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">$<?= number_format($product['price'], 2) ?></td>
                                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200"><?= htmlspecialchars($product['stock_quantity']) ?></td>
                                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200"><?= htmlspecialchars($product['category_id']) ?></td>
                                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200"><?= htmlspecialchars($product['Collections']) ?></td>
                                                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                                                        <img class="w-16 h-16 rounded-full" src="<?= htmlspecialchars($product['image_url']) ?>" alt="Product Image">
                                                    </td>
                                                    <td class="px-6 py-4 text-sm font-medium leading-5 text-right whitespace-no-wrap border-b border-gray-200">
                                                        <a href="remove_product.php?id=<?= $product['product_id'] ?>" class="text-red-600 hover:text-red-900">Remove</a>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm font-medium leading-5 text-right whitespace-no-wrap border-b border-gray-200">
                                                        <a href="Formulaire_Update_product.php?id=<?= $product['product_id'] ?>" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Pagination -->
                        <div class="flex justify-center mt-8">
                            <nav class="inline-flex rounded-md shadow">
                                <?php if ($page > 1): ?>
                                    <a href="product.php?page=<?= $page - 1 ?>&search=<?= urlencode($searchQuery) ?>" class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">
                                        Previous
                                    </a>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <a href="product.php?page=<?= $i ?>&search=<?= urlencode($searchQuery) ?>" class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 <?= $i === $page ? 'bg-orange-100' : '' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>

                                <?php if ($page < $totalPages): ?>
                                    <a href="product.php?page=<?= $page + 1 ?>&search=<?= urlencode($searchQuery) ?>" class="px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">
                                        Next
                                    </a>
                                <?php endif; ?>
                            </nav>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>
</body>

</html>
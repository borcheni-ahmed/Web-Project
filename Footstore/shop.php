<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

require_once 'pdo.php';

// Number of products per page
$products_per_page = 8;

// Get the current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

// Calculate the offset for pagination
$offset = ($page - 1) * $products_per_page;

// Get the selected category (if any)
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;

// Get the selected sort option (if any)
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';

// Fetch products based on the selected category and sort option
$sql = "SELECT * FROM products";
if ($category_id) {
    $sql .= " WHERE category_id = :category_id";
}
switch ($sort) {
    case 'price_low_high':
        $sql .= " ORDER BY price ASC";
        break;
    case 'price_high_low':
        $sql .= " ORDER BY price DESC";
        break;
    default:
        $sql .= " ORDER BY product_id DESC";
        break;
}
$sql .= " LIMIT :limit OFFSET :offset";

$stmt = $pdo->prepare($sql);
if ($category_id) {
    $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
}
$stmt->bindValue(':limit', $products_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$products = $stmt->fetchAll();

// Fetch total number of products for pagination
$total_products_sql = "SELECT COUNT(*) FROM products";
if ($category_id) {
    $total_products_sql .= " WHERE category_id = :category_id";
}
$total_products_stmt = $pdo->prepare($total_products_sql);
if ($category_id) {
    $total_products_stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
}
$total_products_stmt->execute();
$total_products = $total_products_stmt->fetchColumn();

// Calculate total pages
$total_pages = ceil($total_products / $products_per_page);

// Fetch all categories for the filter dropdown
$categories_sql = "SELECT * FROM categories";
$categories_stmt = $pdo->query($categories_sql);
$categories = $categories_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shop - Footcap</title>
    <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml" />
    <link rel="stylesheet" href="./assets/css/style.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap"
        rel="stylesheet" />
    <style>
        body {
            background: var(--cultured);
            padding-top: 90px;
            margin: 0;
        }

        .shop-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .shop-container h2 {
            font-size: 2.4rem;
            color: var(--rich-black-fogra-29);
            margin-bottom: 20px;
        }

        .filter-section {
            margin-bottom: 30px;
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .filter-section label {
            font-size: 1.4rem;
            color: var(--onyx);
        }

        .filter-section select {
            padding: 10px;
            font-size: 1.4rem;
            border: 1px solid var(--gainsboro);
            border-radius: 5px;
        }

        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .product-card {
            background: var(--white);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-card .card-content {
            padding: 15px;
            text-align: center;
        }

        .product-card .card-title {
            font-size: 1.6rem;
            color: var(--rich-black-fogra-29);
            margin-bottom: 10px;
        }

        .product-card .card-price {
            font-size: 1.4rem;
            color: var(--bittersweet);
            font-weight: var(--fw-600);
        }

        .card-action-list {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .card-action-btn {
            background: var(--white);
            color: var(--rich-black-fogra-29);
            font-size: 18px;
            padding: 10px;
            border-radius: 50%;
            transition: var(--transition-1);
        }

        .card-action-btn:hover {
            background: var(--bittersweet);
            color: var(--white);
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 30px;
            gap: 10px;
        }

        .pagination a {
            padding: 10px 15px;
            background: var(--white);
            color: var(--rich-black-fogra-29);
            border: 1px solid var(--gainsboro);
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .pagination a:hover {
            background: var(--bittersweet);
            color: var(--white);
        }

        .pagination .current-page {
            background: var(--bittersweet);
            color: var(--white);
        }

        .profile-dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--white);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            z-index: 1;
            min-width: 150px;
        }

        .dropdown-content a {
            display: block;
            padding: 10px 15px;
            color: var(--rich-black-fogra-29);
            text-decoration: none;
            font-size: 1.4rem;
            transition: background 0.3s ease;
        }

        .dropdown-content a:hover {
            background: var(--cultured);
        }

        /* Show dropdown on hover */
        .profile-dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>

<body>
    <header class="header" data-header>
        <div class="container">
            <div class="overlay" data-overlay></div>

            <a href="index.html" class="logo">
                <img
                    src="./assets/images/logo.svg"
                    width="160"
                    height="50"
                    alt="Footcap logo" />
            </a>

            <button class="nav-open-btn" data-nav-open-btn aria-label="Open Menu">
                <ion-icon name="menu-outline"></ion-icon>
            </button>

            <nav class="navbar" data-navbar>
                <button
                    class="nav-close-btn"
                    data-nav-close-btn
                    aria-label="Close Menu">
                    <ion-icon name="close-outline"></ion-icon>
                </button>

                <a href="index.php" class="logo">
                    <img
                        src="./assets/images/logo.svg"
                        width="190"
                        height="50"
                        alt="Footcap logo" />
                </a>

                <ul class="navbar-list">
                    <li class="navbar-item">
                        <a href="index.php" class="navbar-link">Home</a>
                    </li>

                    <li class="navbar-item">
                        <a href="about.php" class="navbar-link">About</a>
                    </li>

                    <li class="navbar-item">
                        <a href="products.php" class="navbar-link">Products</a>
                    </li>

                    <li class="navbar-item">
                        <a href="shop.php" class="navbar-link">Shop</a>
                    </li>

                    <li class="navbar-item">
                        <a href="blog.php" class="navbar-link">Blog</a>
                    </li>

                    <li class="navbar-item">
                        <a href="contact.php" class="navbar-link">Contact</a>
                    </li>
                </ul>

                <ul class="nav-action-list">
                    <li>
                        <button class="nav-action-btn" aria-label="Search" onclick="window.location.href='search.php'">
                            <ion-icon name="search-outline" aria-hidden="true"></ion-icon>
                            <span class="nav-action-text">Search</span>
                        </button>
                    </li>

                    <li class="profile-dropdown">
                        <a href="profil.php" class="nav-action-btn">
                            <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
                            <span class="nav-action-text">Profile</span>
                        </a>
                        <div class="dropdown-content">
                            <a href="profil.php">Profile</a>
                            <a href="logout.php">Logout</a>
                        </div>
                    </li>

                    <li>
                        <button class="nav-action-btn">
                            <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                            <span class="nav-action-text">Wishlist</span>
                            <data class="nav-action-badge" value="<?php echo isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0; ?>" aria-hidden="true">
                                <?php echo isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0; ?>
                            </data>
                        </button>
                    </li>
                    <li>
                        <a href="bag.php" class="nav-action-btn">
                            <ion-icon name="bag-outline" aria-hidden="true"></ion-icon>
                            <span class="nav-action-text">Bag</span>
                            <data class="nav-action-badge" value="<?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>" aria-hidden="true">
                                <?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
                            </data>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <div class="shop-container">
            <h2>Shop</h2>

            <!-- Filter and Sort Section -->
            <div class="filter-section">
                <label for="category">Filter by Category:</label>
                <select id="category" name="category" onchange="filterProducts()">
                    <option value="all">All</option>
                    <?php foreach ($categories as $category): ?>
                        <option
                            value="<?php echo $category['category_id']; ?>"
                            <?php echo $category_id == $category['category_id'] ? 'selected' : ''; ?>>
                            <?php echo $category['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="sort">Sort by:</label>
                <select id="sort" name="sort" onchange="sortProducts()">
                    <option value="default" <?php echo $sort === 'default' ? 'selected' : ''; ?>>Default</option>
                    <option value="price_low_high" <?php echo $sort === 'price_low_high' ? 'selected' : ''; ?>>Price: Low to High</option>
                    <option value="price_high_low" <?php echo $sort === 'price_high_low' ? 'selected' : ''; ?>>Price: High to Low</option>
                </select>
            </div>

            <!-- Product List -->
            <div class="product-list">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" />
                        <div class="card-content">
                            <h3 class="card-title"><?php echo $product['name']; ?></h3>
                            <p class="card-price">$<?php echo number_format($product['price'], 2); ?></p>
                            <ul class="card-action-list">
                                <li class="card-action-item">
                                    <button class="card-action-btn" aria-labelledby="card-label-1" onclick="addToCart(<?php echo $product['product_id']; ?>)">
                                        <ion-icon name="cart-outline"></ion-icon>
                                    </button>
                                    <div class="card-action-tooltip" id="card-label-1">Add to Cart</div>
                                </li>
                                <li class="card-action-item">
                                    <button class="card-action-btn" aria-labelledby="card-label-2" onclick="addToWishlist(<?php echo $product['product_id']; ?>)">
                                        <ion-icon name="heart-outline"></ion-icon>
                                    </button>
                                    <div class="card-action-tooltip" id="card-label-2">Add to Wishlist</div>
                                </li>
                                <li class="card-action-item">
                                    <a class="card-action-btn" aria-labelledby="card-label-3" href="detail_Product.php?id=<?php echo $product['product_id']; ?>">
                                        <ion-icon name="eye-outline"></ion-icon>
                                    </a>
                                    <div class="card-action-tooltip" id="card-label-3">Quick View</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="shop.php?page=<?php echo $page - 1; ?>&category=<?php echo $category_id; ?>&sort=<?php echo $sort; ?>">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a
                        href="shop.php?page=<?php echo $i; ?>&category=<?php echo $category_id; ?>&sort=<?php echo $sort; ?>"
                        class="<?php echo $i === $page ? 'current-page' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="shop.php?page=<?php echo $page + 1; ?>&category=<?php echo $category_id; ?>&sort=<?php echo $sort; ?>">Next</a>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-top section">
            <div class="container">
                <div class="footer-brand">
                    <a href="index.html" class="logo">
                        <img
                            src="./assets/images/logo.svg"
                            width="160"
                            height="50"
                            alt="Footcap logo" />
                    </a>

                    <ul class="social-list">
                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-facebook"></ion-icon>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-twitter"></ion-icon>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-pinterest"></ion-icon>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="social-link">
                                <ion-icon name="logo-linkedin"></ion-icon>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="footer-link-box">
                    <ul class="footer-list">
                        <li>
                            <p class="footer-list-title">Contact Us</p>
                        </li>

                        <li>
                            <address class="footer-link">
                                <ion-icon name="location"></ion-icon>
                                <span class="footer-link-text">
                                    2751 S Parker Rd, Aurora, CO 80014, United States
                                </span>
                            </address>
                        </li>

                        <li>
                            <a href="tel:+557343673257" class="footer-link">
                                <ion-icon name="call"></ion-icon>
                                <span class="footer-link-text">+557343673257</span>
                            </a>
                        </li>

                        <li>
                            <a href="mailto:footcap@help.com" class="footer-link">
                                <ion-icon name="mail"></ion-icon>
                                <span class="footer-link-text">footcap@help.com</span>
                            </a>
                        </li>
                    </ul>

                    <ul class="footer-list">
                        <li>
                            <p class="footer-list-title">My Account</p>
                        </li>

                        <li>
                            <a href="#" class="footer-link">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                                <span class="footer-link-text">My Account</span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="footer-link">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                                <span class="footer-link-text">View Cart</span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="footer-link">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                                <span class="footer-link-text">Wishlist</span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="footer-link">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                                <span class="footer-link-text">Compare</span>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="footer-link">
                                <ion-icon name="chevron-forward-outline"></ion-icon>
                                <span class="footer-link-text">New Products</span>
                            </a>
                        </li>
                    </ul>

                    <div class="footer-list">
                        <p class="footer-list-title">Opening Time</p>

                        <table class="footer-table">
                            <tbody>
                                <tr class="table-row">
                                    <th class="table-head" scope="row">Mon - Tue:</th>
                                    <td class="table-data">8AM - 10PM</td>
                                </tr>

                                <tr class="table-row">
                                    <th class="table-head" scope="row">Wed:</th>
                                    <td class="table-data">8AM - 7PM</td>
                                </tr>

                                <tr class="table-row">
                                    <th class="table-head" scope="row">Fri:</th>
                                    <td class="table-data">7AM - 11PM</td>
                                </tr>

                                <tr class="table-row">
                                    <th class="table-head" scope="row">Sat:</th>
                                    <td class="table-data">9AM - 5PM</td>
                                </tr>

                                <tr class="table-row">
                                    <th class="table-head" scope="row">Sun:</th>
                                    <td class="table-data">Closed</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <p class="copyright">
                    &copy; 2023 Footcap. All Rights Reserved.
                </p>
            </div>
        </div>
    </footer>

    <script>
        function filterProducts() {
            const category = document.getElementById('category').value;
            const sort = document.getElementById('sort').value;
            window.location.href = `shop.php?category=${category}&sort=${sort}`;
        }

        function sortProducts() {
            const category = document.getElementById('category').value;
            const sort = document.getElementById('sort').value;
            window.location.href = `shop.php?category=${category}&sort=${sort}`;
        }

        function addToCart(productId) {
            fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update the cart badge
                        const cartBadge = document.querySelector('.nav-action-badge[value]');
                        if (cartBadge) {
                            cartBadge.setAttribute('value', data.cart_count);
                            cartBadge.textContent = data.cart_count;
                        }
                    } else {
                        alert('Failed to add product to cart.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding the product to the cart.');
                });
        }
        // Function to add a product to the wishlist
        function addToWishlist(productId) {
            fetch('add_to_wishlist.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        product_id: productId
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const wishlistBadge = document.querySelector('.nav-action-badge[value]');
                        if (wishlistBadge) {
                            const currentCount = parseInt(wishlistBadge.getAttribute('value'));
                            wishlistBadge.setAttribute('value', currentCount + 1);
                            wishlistBadge.textContent = currentCount + 1;
                        }
                    } else {
                        alert('Failed to add product to wishlist.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while adding the product to the wishlist.');
                });
        }
        document.addEventListener('DOMContentLoaded', function() {
            const profileDropdown = document.querySelector('.profile-dropdown');
            const dropdownContent = document.querySelector('.dropdown-content');

            profileDropdown.addEventListener('mouseenter', function() {
                dropdownContent.style.display = 'block';
            });

            profileDropdown.addEventListener('mouseleave', function() {
                dropdownContent.style.display = 'none';
            });
        });
    </script>

    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</body>

</html>
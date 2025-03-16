<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require_once 'pdo.php';

// Check if the product ID is provided in the URL
if (!isset($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$product_id = (int)$_GET['id'];

// Fetch the product details
$sql = "SELECT * FROM products WHERE product_id = :product_id";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$product = $stmt->fetch();

// If the product doesn't exist, redirect to the products page
if (!$product) {
    header('Location: products.php');
    exit;
}

// Fetch related products (products from the same category)
$sql = "SELECT * FROM products WHERE category_id = :category_id AND product_id != :product_id LIMIT 4";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':category_id', $product['category_id'], PDO::PARAM_INT);
$stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
$stmt->execute();
$related_products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $product['name']; ?> - Footcap</title>
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

        .product-detail-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .product-detail {
            display: flex;
            gap: 40px;
            margin-bottom: 40px;
        }

        .product-image {
            flex: 1;
            max-width: 500px;
        }

        .product-image img {
            width: 100%;
            border-radius: 10px;
        }

        .product-info {
            flex: 1;
        }

        .product-info h1 {
            font-size: 2.4rem;
            color: var(--rich-black-fogra-29);
            margin-bottom: 10px;
        }

        .product-info .price {
            font-size: 1.8rem;
            color: var(--bittersweet);
            font-weight: var(--fw-600);
            margin-bottom: 20px;
        }

        .product-info .description {
            font-size: 1.4rem;
            color: var(--onyx);
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .product-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .product-actions .btn {
            padding: 10px 20px;
            font-size: 1.4rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .product-actions .btn-primary {
            background: var(--bittersweet);
            color: var(--white);
            border: none;
        }

        .product-actions .btn-primary:hover {
            background: var(--salmon);
        }

        .product-actions .btn-secondary {
            background: transparent;
            border: 1px solid var(--bittersweet);
            color: var(--bittersweet);
        }

        .product-actions .btn-secondary:hover {
            background: var(--bittersweet);
            color: var(--white);
        }

        .related-products {
            margin-top: 40px;
        }

        .related-products h2 {
            font-size: 2rem;
            color: var(--rich-black-fogra-29);
            margin-bottom: 20px;
        }

        .related-products .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .related-products .product-card {
            background: var(--white);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .related-products .product-card:hover {
            transform: translateY(-5px);
        }

        .related-products .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .related-products .product-card .card-content {
            padding: 15px;
            text-align: center;
        }

        .related-products .product-card .card-title {
            font-size: 1.6rem;
            color: var(--rich-black-fogra-29);
            margin-bottom: 10px;
        }

        .related-products .product-card .card-price {
            font-size: 1.4rem;
            color: var(--bittersweet);
            font-weight: var(--fw-600);
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

            <a href="index.php" class="logo">
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
                        <button class="nav-action-btn" onclick="window.location.href='search.php';">
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
                        <button class="nav-action-btn" onclick="window.location.href='heart.php';">
                            <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
                            <span class="nav-action-text">Wishlist</span>
                            <data class="nav-action-badge" value="<?php echo isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0; ?>" aria-hidden="true">
                                <?php echo isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0; ?>
                            </data>
                        </button>
                    </li>
                    <li>
                        <a href="bag.php" class="nav-action-btn" onclick="window.location.href='bag.php';">
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
        <div class="product-detail-container">
            <!-- Product Details -->
            <div class="product-detail">
                <div class="product-image">
                    <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" />
                </div>
                <div class="product-info">
                    <h1><?php echo $product['name']; ?></h1>
                    <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                    <p class="description"><?php echo $product['description']; ?></p>
                    <div class="product-actions">
                        <button class="btn btn-primary" onclick="addToCart(<?php echo $product['product_id']; ?>)">Add to Cart</button>
                        <button class="btn btn-secondary" onclick="addToWishlist(<?php echo $product['product_id']; ?>)">Add to Wishlist</button>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <div class="related-products">
                <h2>Related Products</h2>
                <div class="product-list">
                    <?php foreach ($related_products as $related_product): ?>
                        <div class="product-card">
                            <a href="detail_Product.php?id=<?php echo $related_product['product_id']; ?>">
                                <img src="<?php echo $related_product['image_url']; ?>" alt="<?php echo $related_product['name']; ?>" />
                                <div class="card-content">
                                    <h3 class="card-title"><?php echo $related_product['name']; ?></h3>
                                    <p class="card-price">$<?php echo number_format($related_product['price'], 2); ?></p>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
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
                                    <td class="table-data">7AM - 12PM</td>
                                </tr>

                                <tr class="table-row">
                                    <th class="table-head" scope="row">Sat:</th>
                                    <td class="table-data">9AM - 8PM</td>
                                </tr>

                                <tr class="table-row">
                                    <th class="table-head" scope="row">Sun:</th>
                                    <td class="table-data">Closed</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="footer-list">
                        <p class="footer-list-title">Newsletter</p>

                        <p class="newsletter-text">
                            Authoritatively morph 24/7 potentialities with error-free
                            partnerships.
                        </p>

                        <form action="" class="newsletter-form">
                            <input
                                type="email"
                                name="email"
                                required
                                placeholder="Email Address"
                                class="newsletter-input" />

                            <button type="submit" class="btn btn-primary">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="container">
                <p class="copyright">
                    &copy; 2022 <a href="#" class="copyright-link">codewithsadee</a>.
                    All Rights Reserved
                </p>
            </div>
        </div>
    </footer>

    <!-- Go Top Button -->
    <a href="#top" class="go-top-btn" data-go-top>
        <ion-icon name="arrow-up-outline"></ion-icon>
    </a>

    <!-- Scripts -->
    <script src="./assets/js/script.js"></script>
    <script
        type="module"
        src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script
        nomodule"
        src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script>
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
</body>

</html>
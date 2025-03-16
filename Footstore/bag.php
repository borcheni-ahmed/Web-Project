<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require_once 'pdo.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$cart_items = [];

if (isset($_SESSION['cart'])) {
    $cart_items = $_SESSION['cart'];
}

$total_price = 0;
foreach ($cart_items as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Your Bag - Footcap</title>
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
            /* Adjust for header height */
            margin: 0;
        }

        .bag-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .bag-container h2 {
            font-size: 2.4rem;
            color: var(--rich-black-fogra-29);
            margin-bottom: 20px;
        }

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .cart-table th,
        .cart-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid var(--gainsboro);
        }

        .cart-table th {
            background: var(--white);
            font-weight: var(--fw-600);
        }

        .cart-table td img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }

        .quantity-input {
            width: 60px;
            padding: 5px;
            text-align: center;
            border: 1px solid var(--gainsboro);
            border-radius: 5px;
        }

        .remove-btn {
            color: var(--bittersweet);
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .remove-btn:hover {
            color: var(--salmon);
        }

        .total-price {
            font-size: 1.8rem;
            font-weight: var(--fw-600);
            text-align: right;
            margin-top: 20px;
        }

        .checkout-btn {
            display: block;
            width: 100%;
            max-width: 300px;
            margin: 20px auto;
            padding: 15px;
            font-size: 1.6rem;
            background: var(--bittersweet);
            color: var(--white);
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .checkout-btn:hover {
            background: var(--salmon);
        }

        .empty-cart {
            text-align: center;
            font-size: 1.6rem;
            color: var(--onyx);
            margin-top: 50px;
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
    <!-- Header -->
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
        <div class="bag-container">
            <h2>Your Bag</h2>

            <?php if (!empty($cart_items)): ?>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th style="width: 200px;">Product</th>
                            <th style="width: 90px;">Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td>
                                    <img src="<?php echo $item['image_url']; ?>" alt="<?php echo $item['name']; ?>" />
                                    <br>
                                    <span><?php echo $item['name']; ?></span>
                                </td>
                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <input
                                        type="number"
                                        class="quantity-input"
                                        value="<?php echo $item['quantity']; ?>"
                                        min="1"
                                        onchange="updateQuantity(<?php echo $item['product_id']; ?>, this.value)" />
                                </td>
                                <td>$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                                <td>
                                    <span class="remove-btn" onclick="removeItem(<?php echo $item['product_id']; ?>)">Remove</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="total-price">
                    Total: $<?php echo number_format($total_price, 2); ?>
                </div>

                <form action="save_order.php" method="POST">
                    <button type="submit" class="checkout-btn">Proceed to Checkout</button>
                </form>
            <?php else: ?>
                <div class="empty-cart">Your bag is empty.</div>
            <?php endif; ?>
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
        function updateQuantity(productId, quantity) {
            fetch(`update_quantity.php?productId=${productId}&quantity=${quantity}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Reload the page to reflect the changes
                    } else {
                        alert('Failed to update quantity: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the quantity.');
                });
        }

        function removeItem(productId) {
            fetch(`remove_item.php?productId=${productId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Reload the page to reflect the changes
                    } else {
                        alert('Failed to remove item: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while removing the item.');
                });
        }
    </script>
</body>

</html>
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
require_once 'pdo.php';

// Number of blog posts per page
$posts_per_page = 6;

// Get the current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

// Calculate the offset for pagination
$offset = ($page - 1) * $posts_per_page;

// Fetch blog posts from the database
$sql = "SELECT * FROM blog_posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':limit', $posts_per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$blog_posts = $stmt->fetchAll();

// Fetch total number of blog posts for pagination
$total_posts_sql = "SELECT COUNT(*) FROM blog_posts";
$total_posts_stmt = $pdo->query($total_posts_sql);
$total_posts = $total_posts_stmt->fetchColumn();

// Calculate total pages
$total_pages = ceil($total_posts / $posts_per_page);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Blog - Footcap</title>
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

        .blog-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .blog-container h2 {
            font-size: 2.4rem;
            color: var(--rich-black-fogra-29);
            margin-bottom: 20px;
        }

        .blog-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .blog-post {
            background: var(--white);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .blog-post:hover {
            transform: translateY(-5px);
        }

        .blog-post img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .blog-post .post-content {
            padding: 20px;
        }

        .blog-post .post-title {
            font-size: 1.8rem;
            color: var(--rich-black-fogra-29);
            margin-bottom: 10px;
        }

        .blog-post .post-excerpt {
            font-size: 1.4rem;
            color: var(--onyx);
            margin-bottom: 15px;
        }

        .blog-post .read-more-btn {
            display: inline-block;
            padding: 10px 20px;
            background: var(--bittersweet);
            color: var(--white);
            font-size: 1.4rem;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .blog-post .read-more-btn:hover {
            background: var(--salmon);
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
        <div class="blog-container">
            <h2>Our Blog</h2>

            <!-- Blog List -->
            <div class="blog-list">
                <?php foreach ($blog_posts as $post): ?>
                    <div class="blog-post">
                        <img src="<?php echo $post['image_url']; ?>" alt="<?php echo $post['title']; ?>" />
                        <div class="post-content">
                            <h3 class="post-title"><?php echo $post['title']; ?></h3>
                            <p class="post-excerpt"><?php echo substr($post['content'], 0, 150) . '...'; ?></p>
                            <a href="blog-post.php?id=<?php echo $post['post_id']; ?>" class="read-more-btn">Read More</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="blog.php?page=<?php echo $page - 1; ?>">Previous</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a
                        href="blog.php?page=<?php echo $i; ?>"
                        class="<?php echo $i === $page ? 'current-page' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $total_pages): ?>
                    <a href="blog.php?page=<?php echo $page + 1; ?>">Next</a>
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
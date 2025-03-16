<?php
// Start the session
session_start();
if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit;
}
// Include the database connection file
require_once 'pdo.php';

// Fetch categories from the database
$categories = [];
try {
  $sql = "SELECT * FROM categories";
  $stmt = $pdo->query($sql);
  $categories = $stmt->fetchAll();
} catch (PDOException $e) {
  die("Error fetching categories: " . $e->getMessage());
}

// Fetch products based on the selected category (if any)
$selected_category = isset($_GET['category']) ? $_GET['category'] : null;
$bestseller_products = [];

try {
  if ($selected_category) {
    // Fetch products for the selected category
    $sql = "SELECT * FROM products WHERE category_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$selected_category]);
  } else {
    // Fetch all products if no category is selected
    $sql = "SELECT * FROM products";
    $stmt = $pdo->query($sql);
  }
  $bestseller_products = $stmt->fetchAll();
} catch (PDOException $e) {
  die("Error fetching products: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Footcap - Find your footwear</title>
  <link rel="shortcut icon" href="./favicon.svg" type="image/svg+xml" />
  <link rel="stylesheet" href="./assets/css/style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap"
    rel="stylesheet" />
  <link rel="preload" href="./assets/images/hero-banner.png" as="image" />
  <style>
    /* Profile Dropdown */
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

<body id="top">
  <header class="header" data-header>
    <div class="container">
      <div class="overlay" data-overlay></div>

      <a href="#" class="logo">
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
            <button class="nav-action-btn" onclick="window.location.href='search.php'">
              <ion-icon name="search-outline" aria-hidden="true"></ion-icon>
              <span class="nav-action-text">Search</span>
            </button>
          </li>

          <li class="profile-dropdown">
            <a href="profil.php" class="nav-action-btn">
              <ion-icon name="person-outline" aria-hidden="true"></ion-icon>
              <span class="nav-action-text">Profile</span>
            </a>
            <!-- Dropdown Content -->
            <div class="dropdown-content">
              <a href="profil.php">Profile</a>
              <a href="logout.php">Logout</a>
            </div>
          </li>
          <li>
            <button class="nav-action-btn" onclick="window.location.href='heart.php'">
              <ion-icon name="heart-outline" aria-hidden="true"></ion-icon>
              <span class="nav-action-text">Wishlist</span>
              <data class="nav-action-badge" value="<?php echo isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0; ?>" aria-hidden="true">
                <?php echo isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0; ?>
              </data>
            </button>
          </li>
          <li>
            <a href="bag.php" class="nav-action-btn" onclick="window.location.href='bag.php'">
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

  <main>
    <article>
      <!-- Hero Section -->
      <section
        class="section hero"
        style="background-image: url('./assets/images/hero-banner.png')">
        <div class="container">
          <h2 class="h1 hero-title">
            New Summer <strong>Shoes Collection</strong>
          </h2>

          <p class="hero-text">
            Competently expedite alternative benefits whereas leading-edge
            catalysts for change. Globally leverage existing an expanded array
            of leadership.
          </p>

          <button class="btn btn-primary" onclick="window.location.href='shop.php'">
            <span>Shop Now</span>
            <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
          </button>
        </div>
      </section>

      <!-- Collection Section -->
      <section class="section collection">
        <div class="container">
          <ul class="collection-list has-scrollbar">
            <li>
              <div class="collection-card" style="background-image: url('./assets/images/collection-1.jpg')">
                <h3 class="h4 card-title">Men Collections</h3>
                <a href="collection.php?col=Men" class="btn btn-secondary">
                  <span>Explore All</span>
                  <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                </a>
              </div>
            </li>

            <li>
              <div class="collection-card" style="background-image: url('./assets/images/collection-2.jpg')">
                <h3 class="h4 card-title">Women Collections</h3>
                <a href="collection.php?col=Women" class="btn btn-secondary">
                  <span>Explore All</span>
                  <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                </a>
              </div>
            </li>

            <li>
              <div class="collection-card" style="background-image: url('./assets/images/collection-3.jpg')">
                <h3 class="h4 card-title">Sports Collections</h3>
                <a href="collection.php?col=Sports" class="btn btn-secondary">
                  <span>Explore All</span>
                  <ion-icon name="arrow-forward-outline" aria-hidden="true"></ion-icon>
                </a>
              </div>
            </li>
          </ul>
        </div>
      </section>

      <!-- Bestseller Products Section -->
      <section class="section product">
        <div class="container">
          <h2 class="h2 section-title">Bestsellers Products</h2>

          <!-- Dynamic Filter List -->
          <ul class="filter-list">
            <li>
              <a href="?" class="filter-btn <?php echo !$selected_category ? 'active' : ''; ?>">All</a>
            </li>
            <?php foreach ($categories as $category): ?>
              <li>
                <a href="?category=<?php echo $category['category_id']; ?>" class="filter-btn <?php echo $selected_category == $category['category_id'] ? 'active' : ''; ?>">
                  <?php echo htmlspecialchars($category['name']); ?>
                </a>
              </li>
            <?php endforeach; ?>
          </ul>

          <!-- Product List -->
          <ul class="product-list">
            <?php foreach ($bestseller_products as $product): ?>
              <li class="product-item">
                <div class="product-card" tabindex="0">
                  <figure class="card-banner">
                    <img
                      src="<?php echo $product['image_url']; ?>"
                      width="312"
                      height="350"
                      loading="lazy"
                      alt="<?php echo $product['name']; ?>"
                      class="image-contain" />
                    <div class="card-badge">New</div>
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
                  </figure>

                  <div class="card-content">
                    <div class="card-cat">
                      <a href="#" class="card-cat-link">Men</a> /
                      <a href="#" class="card-cat-link">Women</a>
                    </div>
                    <h3 class="h3 card-title">
                      <a href="#"><?php echo $product['name']; ?></a>
                    </h3>
                    <data class="card-price" value="<?php echo $product['price']; ?>">$<?php echo number_format($product['price'], 2); ?></data>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </section>

      <!-- Services Section -->
      <section class="section service">
        <div class="container">
          <ul class="service-list">
            <li class="service-item">
              <div class="service-card">
                <div class="card-icon">
                  <img
                    src="./assets/images/service-1.png"
                    width="53"
                    height="28"
                    loading="lazy"
                    alt="Service icon" />
                </div>
                <div>
                  <h3 class="h4 card-title">Free Shipping</h3>
                  <p class="card-text">All orders over <span>$150</span></p>
                </div>
              </div>
            </li>

            <li class="service-item">
              <div class="service-card">
                <div class="card-icon">
                  <img
                    src="./assets/images/service-2.png"
                    width="43"
                    height="35"
                    loading="lazy"
                    alt="Service icon" />
                </div>
                <div>
                  <h3 class="h4 card-title">Quick Payment</h3>
                  <p class="card-text">100% secure payment</p>
                </div>
              </div>
            </li>

            <li class="service-item">
              <div class="service-card">
                <div class="card-icon">
                  <img
                    src="./assets/images/service-3.png"
                    width="40"
                    height="40"
                    loading="lazy"
                    alt="Service icon" />
                </div>
                <div>
                  <h3 class="h4 card-title">Free Returns</h3>
                  <p class="card-text">Money back in 30 days</p>
                </div>
              </div>
            </li>

            <li class="service-item">
              <div class="service-card">
                <div class="card-icon">
                  <img
                    src="./assets/images/service-4.png"
                    width="40"
                    height="40"
                    loading="lazy"
                    alt="Service icon" />
                </div>
                <div>
                  <h3 class="h4 card-title">24/7 Support</h3>
                  <p class="card-text">Get Quick Support</p>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </section>

      <!-- Instagram Posts Section -->
      <section class="section insta-post">
        <ul class="insta-post-list has-scrollbar">
          <?php for ($i = 1; $i <= 8; $i++): ?>
            <li class="insta-post-item">
              <img
                src="./assets/images/insta-<?php echo $i; ?>.jpg"
                width="100"
                height="100"
                loading="lazy"
                alt="Instagram post"
                class="insta-post-banner image-contain" />
              <a href="#" class="insta-post-link">
                <ion-icon name="logo-instagram"></ion-icon>
              </a>
            </li>
          <?php endfor; ?>
        </ul>
      </section>
    </article>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="footer-top section">
      <div class="container">
        <div class="footer-brand">
          <a href="#" class="logo">
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
    nomodule
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